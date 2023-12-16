<!DOCTYPE html>
<html>

<head>
    <?php require(__DIR__ . "/head.html"); ?>
</head>
<body>
    <section class="page_404">
	    <div class="container">
		    <div class="row">	
		        <div class="col-sm-12 ">
		            <div class="col-sm-10 col-sm-offset-1  text-center">
		                <div class="four_zero_four_bg"></div>
		                <div class="contant_box_404">
		                    <h3 class="h2">Dommage</h3>
		                    <p>L'API WordPress ne t'aime PAS (ou n'aime pas les requêtes trop rapprochées)</p>
		                    <p id="attenteMessage">Attends quelques secondes ................</p>
                            <a href="./index.php" id="lien404" class="link_404">Retente ta chance!!</a>
	                    </div>
		            </div>
		        </div>
		    </div>
	    </div>
    </section>
</body>
</html>

<script>
    const attenteMessage = document.getElementById('attenteMessage');
    document.getElementById('lien404').style.display = 'none';
    function basculerAffichage() {
        attenteMessage.style.display = 'none';
        document.getElementById('lien404').style.display = 'block';
    }

    // Compte à rebours de 15 secondes
    let tempsRestant = 15;
    const compteARebours = setInterval(function() {
    let text = attenteMessage.textContent;
    attenteMessage.innerText = "Attends quelques secondes : " + ".".repeat(tempsRestant - 1);
    tempsRestant--;
            if (tempsRestant <= 0) {
                clearInterval(compteARebours);
                basculerAffichage();
            }
        }, 1000);
    
    </script>

<style>
/*======================
    404 page
=======================*/
.page_404{ 
    padding:40px 0; 
    background:#fff; 
    font-family: 'Arvo', serif;
}

.page_404  img{ 
    width:100%;
}

.four_zero_four_bg{
    background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
    height: 400px;
    background-position: center;
 }

.four_zero_four_bg h1{
    font-size:80px;
}

.four_zero_four_bg h3{
    font-size:80px;
}

.link_404{
    color: #fff!important;
    padding: 10px 20px;
    background: #39ac31;
    margin: 20px 0;
    display: inline-block;
}

.contant_box_404{ 
    margin-top:-50px;
}

</style>