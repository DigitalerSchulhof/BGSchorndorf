<!DOCTYPE html>
<html lang="de" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Aktualisiert</title>
	</head>
	<body style="padding: 0; margin: 0; overflow: hidden">
		<div id="status" style="top: 0;left: 0;position: fixed;z-index: 100;background: wheat;background: white;width: 100%;height: 70px;text-align: center;line-height: 70px; font-family: sans-serif">Der Digitale Schulhof wird aktualisiert.</div>
		<canvas style="width: 100%; height: 100%; position: fixed; top: 50px; left: 0px;"></canvas>
		<script>
			function random(min, max) {
				return min + Math.random() * (max - min);
			}
			var x = 0, y = 0;
			function spass() {
				var canvas = document.getElementsByTagName('canvas')[0];
				canvas.width = document.documentElement.clientWidth;
				canvas.height = document.documentElement.clientHeight;
				var ctx = canvas.getContext('2d');
				for(var x = 0; x < Math.ceil(document.documentElement.clientWidth / 20)+1; x++) {
					for(var y = 0; y < Math.ceil(document.documentElement.clientHeight/ 20)+1; y++) {
						var h = random(0, 360), s = random(60, 100), l = random(70, 100), a = random(0.8, 1);
						var hsla = [h,s+"%",l+"%",a];
						ctx.beginPath();
						ctx.fillStyle = "hsla("+hsla+")";
						ctx.rect(20*x, 20*y, 20, 20);
						ctx.fill();
					}
				}
			}
			function test() {
				var test = new XMLHttpRequest();
				test.onreadystatechange = function() {
					if(this.readyState == 2) {
						switch(this.status) {
							case 200:
								document.getElementById("status").innerHTML = "Die Website ist wieder errichbar!<br>Seite kann neugeladen werden!";
								document.getElementById("status").style.background = "rgb(102,187,106)";
								document.getElementById("status").style.height = "100px";
								break;
								case 503:
								break;
								default:
									console.log(this.readyState, this.status);
								break;
							}
						}
					};
					test.open("HEAD", "/status");
					test.send();
				}
				window.addEventListener("load", function() {
					window.addEventListener("resize", spass);
					spass();
					setInterval(spass, 3000);
					setInterval(test, 3000);
				});
		</script>
	</body>
</html>
