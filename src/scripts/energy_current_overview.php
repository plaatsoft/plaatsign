<?php

/* 
**  ==========
**  plaatsign
**  ==========
**
**  Created by wplaat
**
**  For more information visit the following website.
**  Website : www.plaatsoft.nl 
**
**  Or send an email to the following address.
**  Email   : info@plaatsoft.nl
**
**  All copyrights reserved (c) 2008-2016 PlaatSoft
*/

// -------------------------------------------------------

define('DEBUG', 0);

// Database credentials
$dbhost = "127.0.0.1";
$dbname = "plaatenergy";
$dbuser = "plaatenergy";
$dbpass = "plaatenergy";

$database = "./../../database.php";
if (!file_exists($database)) {
	$database = "./../database.php";
}
include $database;

plaatsign_db_connect($dbhost, $dbuser, $dbpass, $dbname);

function solarPanelData($id) {

	$sql = 'select vdc1, idc1, vdc2, idc2, vac, iac, pac, fac, etoday, etotal, temp from solar'.$id.' order by id desc limit 0,1 ';
	$result = plaatsign_db_query($sql);
	$row = plaatsign_db_fetch_object($result);

	return $row;
}

// -------------------------------------------------------
 
function drawLabel($im, $y, $text, $font_size=28, $color) {
	
	global $font;
	global $width;
	global $height;
	
	// Get Bounding Box Size
	$text_box = imagettfbbox($font_size, 0, $font, $text);

	// Get your Text Width and Height
	$text_width = $text_box[2]-$text_box[0];
	
	// Calculate coordinates of the text
	$x = ($width/2) - ($text_width/2);

	// Add some shadow to the text
	imagettftext($im, $font_size, 0, $x, $y, $color, $font, $text);
}

function imagelinedashed($im, $offset, $y, $dist, $col) {
    $width = imagesx($im);
    $nextX = $dist * 2;

    for ($x = $offset; $x <= $width; $x += $nextX) {
        imageline($im, $x, $y, $x + $dist - 1, $y, $col);
    }
}

function drawSolarPanel($im, $x, $y) {

	global $width;
	
	$solarpanel = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUr
	DhsAABMTSURBVHhe7Z1hctw2DEbtXizJydr+6V16iE6a6cFSfY6UrGVKS5CECIrvzaBdx1ouRQGfQJBav35feIGp+PLly8u///67/vSDP/744+X3339ff4JZ+G39P0zAt2/fksEvJ
	AD6HcwFGcBEHAX/I58/f375+vXr+hPcHTKASdDd/1nwCx2jY2EOEIBJyAn+DU0HYA6YAkzC6+vr+ioP3GIOyAAmoCSl//PPP9dXcGfIACYgp/iXAte4P2QAE1AS/IIs4P4gADenJo
	gpBt4fBODm1AYxWcC9QQBuTIvgJQu4NwjAjSmd++8hC7gvrALcFC39aVtvK3CTe0IGcFNap+5kAfeEDOCGtL77b2hK8enTp/UnuANkADfEq3BHQfB+IAA3I/epvxLULk8K3gsEoAE
	KCm231QM3j9Zj3ux9l746C9AY7sdVY40QNUI1AChnuSuqhnJqS9C8HedNTl/2JlL/fmY6H2/0GanPfrQrxvTuIAAVWAPu8+fPrsGj9lOfe2RbX3KCbW9ewWftC9TBCFZQEjib6b0t
	g6ikL4+kfn9mEptWaBys4rWZzhvKQQAqqBGAzVpkBdZMRLb/zJI2akWgJvA3aylEM4IAVFDrvHsrFYOSfqQoaceaxej4FsK5Wa14zg4CUEFLR97bJgbPHLwkaI/aLMkCZM9EoHXQP
	9qz8YFz2AlYgdeOuyMWZ19f/WAJLPOav/p79rXfWnbbf04O6se2S3Dbi1DSPyuPnwt2EIBKtC49EjkBU/oVYlfzTMzgOWwEqqTkbtkLBUzO3XKUc7oy+7orZAANGCULsFzq0qnAle
	C69ZABNGCEO5E1pdcfCo18XiNlXpEhA2jA1cVAKzWFsqj1ANy2DWQADYhchc6d9x8R8U7L3L8dCEAjogZKbZVc4hEtA0AA2oEANMLqlAoqmZdwtAj+DW8RsI6d6hPQCNUAoA0azlx
	bAn991w+WAGu2W05teaB2l2BNfqbVdK5bP1O/P7L9uEEdFAEbYl06Oxv67ctEdOeVPUN3UVnO3XFre0/unVVFT51nTr82tv7J9jUJyzKqPjNyzWU0EIDGeDvzts124yxoa7bkngXs
	I0disvFMVFqKJhQgAYB2LAHzlqrmmEc6uwR6s6nEo6lNtd2a3uM1OwhAYyzBJ+dvhYLTEkylps9oKQSpzzgyDwGaHQSgMXLSlPMeWS1XBf7eWglBqu0jg/Ywqg6knPfIaoKoR+DvT
	X0oxZItkf77wD4ABxZnXV89ZxGA9VU+Ku6p2Fjy3taoD+qL+gTjgQB0xhrECrTlrrv+FAf1ySoCFqGMeM53gGVAJyzLgbmXYIQv6lCg5u5A9BgjsEEGMAijfEuP+qi+toS7vx8IgB
	OW9PYZowT/RmsRQAD8QAACcLabTr9rGfwSpjNrhfr8bJcgBEA1AGjPEkw/l7CemY5NsQRR8niLLXfPoiU0vUfvTbVpMZ3DEanjU1bSf8iDDMCJ2rS1RbVfd2EV5Eoen9V79N7a7EP
	nwBJhXBAAJ2qfWFvueusrO3rvIu5NnppTG2qrtj811IoQnPCWB0BTrKn7PsXVz6njnplS9rOUuxa1XTot2J+jSB13ZJ7nNTMIQGNKgncfHKljnpkC8ypKRWBP6pgzS4kI1MEUoBGa
	52pjy+Kk67+UUVI51zy71dd/5aDP0mda2Z+btQ2Nbes9BtOzCgEUUpMWb/ZI6vdnps/uRcl5P1KSLW1GNtAGBKCC2sCXPQZwSUD0JtWnM9sHbuqYXNPYURuoAwEooObOtbdHB079/
	swiOL/6kOrbmT3SYiwRgnIQAAMtA1/2eDe0BtJj5tAbaya0D1br+48MIbCDADxBDtXKQR9tH8BWcYlGqo9H9ih8wip+zwwhyAcBOMAr8GUp50wdd2T7AIpArYC1FoHNIo5VJBCAHZ
	6Bf3ZnSh1/ZFFJ9fXIjvAae4QgDQKwIgfxcj7ZmQNa7p7qY1Qs43c2Hp7XQW0fifCMTL8RSJtTtg08i2Os/9qOxeHe2i15ICeF2otKq761eAjpCLWrfmpDEY8rT/4wkJxAge+FnE3
	O3OKhnI0ZBEC0eAjpDF0btT39zsIficB86NS97Cy9TZFq48iik+rzkeWyBKv7tGBWphQAOVTKEWrNGvgbqbaOLDqpPh+ZFU8hKL12ozPlFKB1Wqn2lrFsNs+HNJoWeNYHZmRKAWg5
	VyXwr8e7PjATPA5ciVYQeET1elTBbykAs2YVCEAD5DwSApaV/Hlctm3JtNmECgEz4llVXpxp/ZQ8Um0cWXRSfT4yCxrTVButbFbmPfOFlCO0NFWtc7CIUW6bPVDfUn1OWe7Sm9r0F
	Gu1HXlMvZl6CrCcv2vqtzjXW33g2ddi67hcFmddX8XD0rdn56wx09jpOK9z1rVvvVFrNKavAaiCvwmBJRBzkfOq3VbfjX8XAThD83zvwNc1Z/VmQWkA/GJxOreUU+0ekTr+yKKS6u
	uRaZxTeI790WfODAJwgJcQHDli6tgjU0EsGupTqq9Htkdjkjqu1gj8cxCADKzOnWN7agOoN6k+HtlewDyCn8DP41X/WQYsLJo7Lxdy/akvi+Our+pZHPRdzUHnaDnPln1pgaU/+3N
	vfS779nuhPkQvMIYUABWBrAEBEJVNkEIWHSUAUVBqqC5h2F1NPh6JEAKw3OldCm4YFtHk6/L5CHSfAmiOr/QIYDY0xe1dI+i6EYjgh5mR77faIFZK1wxAT3UBzE7PJLxbBsAz9AA/
	6BkLXQQg0to+QG8UC72mAl0EINomFoDe9IqJLjUA5v4AH+lRC7hcAEq+y03VUlYLYCSU1lunuYqLq3cLXi4AKnhYBkbHzvyFDTAu1mVuHasvKLmSy2sAluCXIhL8MCryXUu2a80YW
	nBpBmBVxIuTEwAXLDWvqzPeSzMAi8Ix54e7YPHlq7MABADAGQRgBQGAGUEAFqw7nSj+wV2w+vKVuwIvEwCLsvXaFQXgRdTVgMsEgKAGyOPSWNEy4BXoo3LNwqKWfJsQdrktQWr+Vp
	9UO0d2FZd8kgYrdZJHlosuQOr9GHaVybdzSb3/yCzt1nDpKkAOuemPdVMRgAfy19yiXa5vX8oqBK7oY3ItN60i7ceimHwxB2vGegXuW4G9tv/ySDFEwsNvF8FwXw53nwLoJHLJFQo
	9UgwQiVyftNwMLbFTypACADAq0QTAfQrgkfKQ/kNEckLJa0pcimsGYN3SyPZfuDvRtgW7CoAlhbEs/wFEJNc3LcuB3tMAVwHwWPe8Yl4EUIKHb7rvHVANwAs1n2u5LPOn5PtTtgze
	+q5r0Oel+pEy+vaLu/RNvplL6v1H5olbBuC1VGdR2ZB/j30lct+u5i7XySs79Vz2dl8GzGFR2fXVOdHn/0xP/Ik+xh51AE/cBMBygrnLIpaL32OAEQB/eoyxxZdy+2dZCvT0ZRcBs
	N6pc5dGCDCITq6PRlkOdBEAS6BalNDSbuR5ZZT0LxKRx8SrDuDl+xaGEoDIRK9P3Im7jDUCsJA7CJZKKPP/e9NjrC0+NdKDQc0FwGv+D3A3ItQBmguARaksqmo5Nvq04i7Tnpbc6Z
	p5+apHFtBcAHqk33uiZxVkPR+Z9Zp5CUsuLjWAXHKrq5b5f687SQThm4VeY23xrVyf7b1a1VQAPLcs5tJLAOD+RPCt1jHWLQOwqLjl2OgCgEAdc6dr5+XfrWkqAD1PZCP6XBIBOCb
	62ETwrdYx1i0DuNP8P8LUZzZ6jbnFx3L72LMO0EwAIgQqd1fwJoLvthS/ZgJgWaO0nGyPXV8ALRghJm4lAL2XVHIYoY+9uNv1m0YAvLb/jvLQR8sLAnmMMua5PmwtMLaKjSYCEEHp
	eq5AIADX03PMLb4WITbOuI0AAEQkfGysXw5ahZrJNQup9x9ZT1L9Sdly51jfcT367FSfUjZKP3uS6s+RWUi9/8haUJ0BeK3HjjL/H6Wfd2R2H2kRe81WAXLwmjtZ2m0N05R+9Bz7u
	/hytQD0DD6AmWkRe5dmAJY1VMvJWYonPRmlnz2547W0+PLVeyGqBCDC9l8R/QGgjVH62ROupS1WausAVQJgmdt4nVTvOwZToH70HvsIPl1bBwkpABZ6CwDMSwSfrhWAV60Frq9NaG
	nD0lHLx7y+vq6vAO6DVwxIBEqnJMUZQIS7P8BduSoLCCcALTY3AEQkYh3gEgHgEVgAG16PHe8pEgDPu3TNyQBExtO3S2OyahUgB+tSDQIAd8Xq21cscxYJgFfHeLAG7o6Xj5fGpHs
	GcNVcBmAELD5+Re3MLABelUyBAMDd8fTxkjqAawaAAAC8x+rjltS+JH7MOwG9dihZdxZeUSA5Q+eWO+A6L6sYtob++mINVK+4MG/slQDksnRcrWebhWUAk22kTMf2hv76Qn9/kWrj
	yBSjFkxTgFxFFr0VGeAuWGLJEqMijAAsqri+es5o4oIY2rnzNbb4urC0PawAWBjlSyM2RutvBLjGvwghANYNDJYB8Vxa9MKq4uBHlGth8U2Lz1vFxRKr2QJgURbPCxJFAAD2ePqmJ
	aYssZotAJ5BbWl7NAFAsMq587WOEk+mGkAunlsYR5sbIgDljDZ2nr7pFVNZAlD6qGEOI87/PccDyohyTSw+GiGummcAnqkNd1OIjqePesRWlgB4ztGtyxYAd8Hq+5bYyo3ZpwLguf
	wnLINwxeORrRmxz1G4+/W2CoDHcuBTAbB00nr3t4pLFMha4jHqNbHGgCXGcsakqwBY2vasLVhBAOIR6ZpYfNXa72kFAOCOhBYA5v91RMpaRmXEMRypDnAqAJbOWS/UqPP/Ufs9A7P
	4VMspxqkAeKqvp7h4YlVsuI5I16ZlkNbwrB9PawC5sNwFcA0tY+1QALy3VloU0lpcjMKo/Y7EDNfeO8M9i+UmGYD3CXg+ZOHJqP2OBNc+TauYOxQAzzu0JbuIdgfwFjsoJ9q1sfiu
	NeNulWEkBcBalfRUu2gCAJCLp+9aY+4oppMCYKlKlpykZ3YRBYSrHTP4QEn2Ymn/KKa7CICFUeeACEA7Rh1Lb98dUgBGnv97r4xAPdGukcWHPesA2QLA/B+gHZ4+3KIO8EEALHf/k
	nmLpX2AmSiJjdosoEoASrC0P/LuQnZGtmMWPxhOAKwXxjq9iIa3OEI9o18ja4zUCsw7AfAuoFguTrRNHQIBiE/Ea2TxZe/+72P8QwaQC/N/gPaUxEjNzfKdAHjfdS0nN/K8L2L2Mj
	ojj6l3HcDCfhyLM4DZ5v+j938mZvO1mpvlTwGwzP9L1jYtyhZR7Zm+jEPEa2Xx6ZL+W2LyXax/X1ka+K4fc2w5mfVd+eg9qbYwDHtv3vGlWN/4KQCpA49sUaj1Xfmk2sEwLG1WFJO
	pdo5s4+1V6ZstpNrBMCxtJaTaObLtJv5WA7DMOUrm/977CwDuRknMWGJzi/lLBAAA/CkRgLdcQ//LtRIoAGKYzUoKgSLV1pGJ30jPAeZEsW/aCLSo0vrKBtMGABulMVMSo8n0IGWl
	aYlYTijZJoZh702xUkrBdDv5j0mrBRHAsHOrCf6NVLsnlvzHD9aiY0LrjwUqhWG3NcWWYkKx0QLLjfZ1ffEUzS1GfkIPYBZU3FO85lD8NCAAjA8CADAxCADAxCAAABODAABMDAIAM
	DEIAMDEIAAAE4MAAEwMAgAwMdkCYPnWIAAYg+xnAcT3tweNACAyr68K6zxMU4AvX76sr8r5+++/X/7666/1JwBoiTVGX/XooDW9z33SKMV///338s8//1S1AQAfURxbYnmJ/bdg/v
	lsMIZh85hi/1VfIGCZMwDAPVC28FYDIB0HmAul/58+fXp5ywD0D2QBAPOgu78E4OcqgLUQCABjst3931AGsEFBEMPubVr1e+TDd30jAhh2T9sHv0h+2f8yHUg2gGHYmJYKfnH61z7
	0plRjGIaNYYph3dCPOBUAoTczLcCwsUwxexb4Gz+XAXP59u0bKwYXYNmbYTm2JSP0cSaWu/2v6n4mZgGAa7Dsy+h1CUfoI5zDF4IATAwCADAxCADAxFADcGQrmG7mxQg1ACsqaG1m
	LWxBPgiAA5Y/z9yCOwrAHo0nf56+PQhAQ3TH1x3ramYQgA1lUmQE7aAG0Ah9F1uP4J8NjXGL76aEHyAADZBDsjnqOjTWiEAbEIBKCP4+IAJtQAAqULGvd/D33GLbe3uvxl7XAMpBA
	ApRwY/97f3RNdC1gDJYBSgkQuqvgtjXr1/Xn/rAOIwNGUABEZ6IjOL06kPv1Q9dC7KAMhCAAnoFvwJNKa8+P9IdT31Rn3pOiXoL8qgwBSjAugGGFNVOydQCV7ZDBuAMwV9GhKnFDC
	AARqxzTYK/HOvYUQewgwAYsaSlLBPWYxlD6gB2EACAiUEAACYGAQCYGAQAYGIQAICJYSOQEcvXfWkdm7XsOlTZz63u67rwtWFGJACQz+JkEkwsoOnagA2mAAATgwAATAwCADAxCAD
	AxCAAABPDMmABPf4gBjwHV7ZDBlAAa/vx4JqUgQAUwJdVxELXgu9dKAMBKEQOZ3lWHXzQNSD4S3l5+R9Ivxx1MlvJagAAAABJRU5ErkJggg==");

	$newWidth = 200;	
	$src = imagecreatefromstring($solarpanel);	
	$dst = imagecreatetruecolor($newWidth, $newWidth);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newWidth, 256, 256);

	// Copy and merge
	imagecopymerge($im, $dst, $x, $y, 0, 0, $newWidth, $newWidth, 100);
}

function drawStats($im, $id, $x, $y) {

	global $black;
	global $green;
	global $font;
		
	imagettftext($im, 22, 0, $x, $y, $black, $font, "Solar Converter ".$id);
	$y+=40;

	$data = solarPanelData($id);

	//vdc1, idc1, vdc2, idc2, vac, iac, pac, fac, etoday, etotal, temp

	$x+=20;

	if (isset($data->vdc1)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "VDC1 = ". $data->vdc1.' Volt');
		$y+=20;
	}
	
	if (isset($data->idc1)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "VDC1 = ". $data->idc1.' Ampere');
		$y+=30;
	}
	
	if (isset($data->vdc2)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "VDC2 = ". $data->vdc2.' Volt');
		$y+=20;
	}
	
	if (isset($data->idc2)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "VDC2 = ". $data->idc2.' Ampere');
		$y+=30;
	}
	
	if (isset($data->pac)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "Power = ". $data->pac.' Watt');
		$y+=20;
	}

	if (isset($data->etoday)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "EToday = ". $data->etoday.' kWh');
		$y+=20;
	}
	
	if (isset($data->temp)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "Temp = ". $data->temp.' Celcius');
		$y+=20;
	}
	
	if (isset($data->etotal)) {
		imagettftext($im, 14, 0, $x, $y, $black, $font, "ETotal = ". $data->etotal.' kWh');
		$y+=20;
	}
}
	
// -------------------------------------------------------
	
$width = 1920/2;
$height = 1080/2;
$offset = 20;

$font = './../../fonts/arial.ttf';
if (!file_exists($font)) {
	$font = './../fonts/arial.ttf';
}

header('Content-Type: image/png');

$im = imagecreatetruecolor($width, $height);

$white = imagecolorallocate($im, 0xff, 0xff, 0xff);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);
$green = imagecolorallocate($im, 0x00, 0xff, 0x00);
$gray = imagecolorallocate($im, 0x85, 0x85, 0x85);

imagefilledrectangle($im, 0, 0, $width, $height, $white);
drawLabel($im, 38, 'Systeemoverzicht', 24, $black);

drawSolarPanel($im, ($width/2)-420, 50);
drawStats($im, 1, 60, 290);

drawSolarPanel($im, ($width/2)-100, 50);
drawStats($im, 2, ($width/2)-110, 290 );

drawSolarPanel($im, ($width/2)+220, 50);
drawStats($im, 3, ($width/2)+210, 290 );

drawLabel($im, $height-10, 'PlaatSoft 2008-2016 - All Copyright Reserved - PlaatEnergy', 12, $gray);

imagepng($im);
imagedestroy($im);

// -------------------------------------------------------

?>