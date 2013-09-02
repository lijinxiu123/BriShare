</div></div></div></div></div><div class="clear"></div>
    <div id="footer">

<a href="http://tiyanzhimei.com">体验之美</a>  &nbsp; </a> <a href="zuimeia.com">最美应用</a> &nbsp; </a> <a href="http://www.brixd.com">Bri体验科技</a> 

</div>
<?php wp_footer(); ?>

<script>
			var nav=document.getElementById('nav');
			var navTop=nav.offsetTop;

		if(document.getElementById('wpadminbar')){

				document.getElementById('nav').style.top="150px";
				window.onscroll=function(){
				var bodyTop=document.documentElement.scrollTop || document.body.scrollTop;

				if(bodyTop>navTop-30){
					nav.style.position="fixed";
					document.getElementById('nav').style.top="28px";
				}else{
					nav.style.position="absolute";
					document.getElementById('nav').style.top="150px";
				}
				}

		}else{

				document.getElementById('nav').style.top="120px";
				window.onscroll=function(){	
				var bodyTop=document.documentElement.scrollTop || document.body.scrollTop;

				if(bodyTop>navTop-30){
					nav.style.position="fixed";
					document.getElementById('nav').style.top="0px";
				}else{
					nav.style.position="absolute";
					nav.style.top="120px";
					
				}
				

			}


		}
		

			
		


</script>
</body>
</html>