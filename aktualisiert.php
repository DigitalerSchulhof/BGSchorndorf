<!DOCTYPE html>
<html lang="de" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Aktualisiert</title>
	</head>
	<body style="padding: 0; margin: 0; overflow: hidden">
		<canvas style="width: 100%; height: 100%"></canvas>
		<script>
			function random(min, max) {
  			return min + Math.random() * (max - min);
			}
			function spass() {
				var canvas = document.getElementsByTagName('canvas')[0];
				canvas.width = document.documentElement.clientWidth;
				canvas.height = document.documentElement.clientHeight;
        var ctx = canvas.getContext('2d');
				for(var x = 0; x < Math.ceil(document.documentElement.clientWidth / 20)+1; x++) {
					for(var y = 0; y < Math.ceil(document.documentElement.clientHeight / 20)+1; y++) {
						var h = random(0, 360), s = random(60, 100), l = random(70, 100), a = random(0.8, 1);
						var hsla = [h,s+"%",l+"%",a];
						ctx.beginPath();
						ctx.fillStyle = "hsla("+hsla+")";
						ctx.rect(20*x, 20*y, 20, 20);
						ctx.fill();
					}
				}
			}
			window.addEventListener("resize", spass);
			spass();
			setTimeout(function() {location.reload();}, 3000);
		</script>
	</body>
</html>
