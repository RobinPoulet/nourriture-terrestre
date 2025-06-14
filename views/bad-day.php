<!DOCTYPE html>

<html>

<head>
  <title>Pas de nourriture céleste</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Favicon -->
  <link rel="icon" href="../assets/IMG/favicon-32x32.png" type="image/x-icon">
  <link href='https://fonts.googleapis.com/css?family=Carter+One' rel='stylesheet' type='text/css'>
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous"
  >
</head>

<body>
  <div class="container">
    <div class="fon">
      <div class="horizon">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="hill"></div>
      <span class="page-404">&#x1F62D;PAS DE NOURRITURE CELESTE&#x1F62D;</span>
      <span class="not-found">
        &#x1F63F;... cette semaine ...&#x1F63F;
        <img src="<?= $imgSrc ?>" alt="message d'erreur">
      </span>
      <div class="moon-sky"></div>
      <div class="satellite">☄</div>
      <div class="meteores">
    		<div></div>
    		<div></div>
    		<div></div>
    	</div>
      <div class="cosmos-star">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="my-cat">
        <div class="ear-l">
          <div class="ear-fur-l"></div>
        </div>
        <div class="ear-r">
          <div class="ear-fur-r"></div>
        </div>
        <div class="head-cat">
          <div class="tabby-cat"></div>
          <div class="tabby-cat-1"></div>
          <div class="tabby-cat-2"></div>
          <div class="eye-l">
            <div class="eye-lz"></div>
          </div>
          <div class="cat-nose"></div>
          <div class="eye-r">
            <div class="eye-rz"></div>
          </div> 
          <div class="muzzle-cat"></div>
          <div class="	jaws-cat"></div>
          <div class="whiskers">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
        </div>
        <div class="body-cat"></div>
        <div class="body-cat-1"></div>
        <div class="paw-cat-l"><div></div></div>
        <div class="paw-cat-r"><div></div></div>
        <div class="tail-cat"></div>
      </div>
      <div class="rock">
        <div class="rock-mountain_1"></div>
        <div class="rock-mountain_s_1"></div>
        <div class="rock-mountain_2"></div>
        <div class="rock-mountain_s_2"></div>
        <div class="rock-mountain_3"></div>
        <div class="rock-mountain_s_3"></div>
      </div>
    </div>
  </div>
</body>

</html>

<style>
  body {
    background:#000
  }
  .fon {
    overflow:hidden;
    left:-10px;position:absolute;
    width:100%;
    height:100%;
    background:linear-gradient(#000,#002)
  }
  .rock-mountain_1,.rock-mountain_s_1,.rock-mountain_2,.rock-mountain_s_2,.rock-mountain_3,.rock-mountain_s_3 {
    width:140px;
    height:140px;
    background:linear-gradient(90deg,#222,#000);
    transform:rotate(45deg);
    position:absolute;
  }
  .rock{
    position:absolute;
    bottom:3%;
    left:40%
  }
  .rock-mountain_1{
    z-index:8;
    left:140px;
    bottom:25px;
    width:180px;
    height:180px;
  }
  .rock-mountain_s_1 {
    z-index:7;
    transform:rotate(52deg);
    left:125px;
    bottom:25px;
    width:180px;
    height:180px;
    background:#111
  }
  .rock-mountain_2 {
    z-index:11;
    left:-50px;
    bottom:30px;
    width:180px;
    height:180px;
  }
  .rock-mountain_s_2 {
    z-index:10;
    transform:rotate(52deg);
    left:-65px;
    bottom:30px;
    width:180px;
    height:180px;
    background:#111
  }
  .rock-mountain_3 {
    z-index:10;
    left:60px;
    bottom:25px;
    width:180px;
    height:180px;
  }
  .rock-mountain_s_3 {
    z-index:9;
    transform:rotate(52deg);
    left:45px;
    bottom:25px;
    width:180px;
    height:180px;
    background:#111
  }
  .horizon div{
    left:20%;
    z-index:15;
    position:absolute;
    bottom:0;
    border-radius:100px 100px 0 0;
    background:linear-gradient(#333,#111);
    width:200px;
    height:150px
  }
  .horizon div:nth-child(2){
    z-index: 15;
    left: 48%;
    bottom: 0px;
    height: 130px;
    background:linear-gradient(120deg, #333, #111)
  }
  .horizon div:nth-child(3){z-index:14;background:linear-gradient(#222,#111);left:40%;bottom:0px;height:180px;width:150px}
.horizon div:nth-child(4){z-index:14;background:linear-gradient(190deg,#333,#111);left:38%;bottom:20px;height:170px}
.horizon div:nth-child(5){z-index:15;left:25%;bottom:0px;height:170px;background:linear-gradient(210deg,#333,#111)}
.horizon div:nth-child(6){background:linear-gradient(180deg,#444,#111);z-index:14;left:34%;bottom:0px;height:190px}
.horizon div:nth-child(7){background:linear-gradient(170deg,#333,#111);z-index:13;left:55%;bottom:0;height:140px}
.horizon div:nth-child(8){background:linear-gradient(160deg,#444,#111);z-index:14;left:50%;bottom:0;height:160px}
.horizon div:nth-child(9){background:linear-gradient(170deg,#333,#111);z-index:15;left:37%;bottom:0px;height:160px}
.horizon div:nth-child(10){background:linear-gradient(-210deg,#333,#111);z-index:13;left:55%;bottom:0px;height:160px}
.horizon div:nth-child(11){background:linear-gradient(-180deg,#222,#111);z-index:13;left:67%;bottom:0px;height:130px}
.horizon div:nth-child(12){background:linear-gradient(-180deg,#222,#111);z-index:15;left:70%;bottom:0px;height:100px}
.horizon div:nth-child(13){background:linear-gradient(180deg,#333,#111);z-index:110;left:15%;bottom:0px;height:100px}
.horizon div:nth-child(14){background:linear-gradient(180deg,#333,#111);z-index:110;left:75%;bottom:0px;height:100px}
.horizon div:nth-child(15){background:linear-gradient(180deg,#333,#111);z-index:110;left:60%;bottom:0px;height:130px}
.horizon div:nth-child(16){width:85%;height:600px;border-radius:300px 400px 0 0;background:linear-gradient(130deg,#222,#000);z-index:12;left:12%;bottom:-405px}
.satellite{-webkit-animation:satellit-anima 25s linear infinite;-moz-animation:satellit-anima 25s linear infinite;animation:satellit-anima 25s linear infinite;transform:rotate(120deg);z-index:5;color:#888;font-size:18px;position:absolute;left:10%;bottom:0}

.hill {z-index:14;background:linear-gradient(80deg,#333,#111);position:absolute;bottom:5%;left:28%;width:200px;height:190px;border-radius:100px 100px 0 0;}

.moon-sky {
  -webkit-animation:moon-anim 3s linear alternate-reverse infinite;
  -moz-animation:moon-anim 3s linear alternate-reverse infinite;
  animation:moon-anim 3s linear alternate-reverse infinite ;box-shadow:0 0 15px 5px #fc0;width:100px;height:100px;border-radius:50px;background:#fc0;position:absolute;top:10%;left:5%}
.cosmos-star div{-webkit-animation:star-anim 200ms linear alternate-reverse infinite;
  -moz-animation:star-anim 200ms linear alternate-reverse infinite;
  animation:star-anim 200ms linear alternate-reverse infinite;
position:absolute;height:1px;width:1px;box-shadow:0 0 15px 3px #fff;background:#fff;}
.cosmos-star div:nth-child(1){left:75%;top:10%}
.cosmos-star div:nth-child(2){left:45%;top:12%}
.cosmos-star div:nth-child(3){left:20%;top:12%}
.cosmos-star div:nth-child(4){left:30%;top:18%}
.cosmos-star div:nth-child(5){left:92%;top:17%}
.cosmos-star div:nth-child(6){left:60%;top:5%}
.cosmos-star div:nth-child(7){left:67%;top:16%}
.cosmos-star div:nth-child(8){left:5%;top:30%}

.meteores div{position:absolute;top:50px;left:280px;width:400px;height:1px;transform:rotate(-45deg);
background:linear-gradient(to left,transparent 0%,#fff 100%)}
.meteores div:before{content:'';position:absolute;width:4px;height:5px;background:#fff;
border-radius:50%;box-shadow:0 0 14px 4px white;margin-top:-2px}
.meteores div:nth-child(1){top:45%;left:100%;-webkit-animation:meteors 3s linear infinite;
-moz-animation:meteors 3s linear infinite;animation:meteors 3s linear infinite}
.meteores div:nth-child(2){top:100%;left:70%;-webkit-animation:meteors 4s linear infinite;
-moz-animation:meteors 4s linear infinite;animation:meteors 4s linear infinite}
.meteores div:nth-child(3){top:70%;left:20%;-webkit-animation:meteors 2s linear infinite;
-moz-animation:meteors 2s linear infinite;animation:meteors 2s linear infinite}

  .page-404,.not-found{
    transform:rotate(0deg);
    font:45px 'Carter One', cursive; 
    color:#777;
    position:absolute;
    display:block;
    bottom:43%;
    left:46.5%;
    z-index:120;
    text-shadow:2px 2px 2px #000
  }
  .not-found{
    bottom:33%;
    left:60%;
    font-size:30px;
  }

.my-cat{z-index:100;position:absolute;left:35%;bottom:235px}
.ear-l,.ear-r,.ear-fur-l,.ear-fur-r{position:relative;z-index:2;border-radius:0 50px 0 0px;width:12px;height:14px;margin:0px 0 -16px 0px;padding:2px;transform:rotate(-2deg);background:linear-gradient(40deg,#111,#444)}
.ear-r,.ear-fur-r{border-radius:50px 0px 10px 0px;margin:0 25px -10px;transform:rotate(15deg);background:linear-gradient(-50deg,#333,#333,#111);}
.ear-fur-l,.ear-fur-r{border-radius:0 50px 0 20px;padding:0;background:linear-gradient(-30deg,#111,#222);width:10px;height:14px}
.ear-fur-r{margin:0 2px;background:linear-gradient(-290deg,#111,#222);transform:rotate(8deg);border-radius:50px 0px 20px}
.head-cat{z-index:1;position:relative;border-radius:50px;width:40px;height:35px;background:linear-gradient(40deg,#000,#444);box-shadow:0 2px 1px #111}
.tabby-cat,.tabby-cat-1,.tabby-cat-2{width:20px;height:2px; background:#222;position:absolute;margin:2px 10px}
.tabby-cat-1{margin:6px 10px;background:#222}
.tabby-cat-2{height:2px;margin:10px 17px;background:linear-gradient(#111,#222);width:6px}
.muzzle-cat{width:22px;height:15px;background:linear-gradient(60deg,#111, #222);border-radius:50px;top:18px;left:11px;position:absolute}
.whiskers div,.whiskers div:nth-child(3) {height:1px;width:16px;background:linear-gradient(90deg,#000,#555);position:absolute;left:-7px;top:31px;transform:rotate(-15deg)}
.whiskers div:nth-child(1){top:28px;left:-5px;transform:rotate(-5deg)}
.whiskers div:nth-child(2){width:17px;top:35px;left:-5px;transform:rotate(-25deg)}
.whiskers div:nth-child(3),.whiskers div:nth-child(4),.whiskers div:nth-child(5){background:linear-gradient(90deg,#555,#000);transform:rotate(10deg);left:30px;top:31px}
.whiskers div:nth-child(4){left:29px;top:28px;transform:rotate(--2deg);width:20px;}
.whiskers div:nth-child(5){left:27px;top:34px}
.jaws-cat{-webkit-animation:jaws-cat 5s infinite;
-moz-animation:jaws-cat 5s infinite;
  animation:jaws-cat 5s infinite;position:absolute;border-radius:30px 10px;position:absolute;border-radius:20px 20px 70px 70px;width:7px;height:3px;background:linear-gradient(#000,#d46);z-index:50;top:30px;left:17px}
.eye-l,.eye-r{
  -webkit-animation:sleep-cat 7s linear infinite;
-moz-animation:sleep-cat 7s linear infinite;
  animation:sleep-cat 7s linear infinite;position:absolute;border-radius:35px 25px 30px 30px;width:10px;height:7px;background:#df9;z-index:500;top:12px;left:7px}
.eye-r{left:24px!important}
.eye-lz,.eye-rz{
  -webkit-animation:eye-cat 7s linear infinite;
-moz-animation:eye-cat 7s linear infinite;
  animation:eye-cat 7s linear infinite;position:absolute;border-radius:30px 10px;transform:rotate(-45deg);width:7px;height:6px;background:#000;z-index:500;left:2px;top:0px}
.eye-rz{left:1px;}
.cat-nose{position:absolute;border-radius:20px 20px 70px 70px;width:7px;height:7px;background:linear-gradient(#000,#555);z-index:500;margin:19px 16px}
.body-cat{transform:rotate(2deg)}
.body-cat,.body-cat-1 {width:30px;height:60px;border-radius:70px 0px 5px 35px;margin:-10px -7px;background:linear-gradient(120deg,#000,#111,#222);position:absolute}
.body-cat-1{border-radius:25px 80px 25px 25px;height:65px;margin:-14px 6px}
.paw-cat-l,.paw-cat-r {position:absolute;width:12px;height:46px;margin:5px 4px;background:linear-gradient(150deg,rgba(1,1,1,.4) ,rgba(34,34,34,.8)),linear-gradient(92deg,#111 3px,#222 70%,#111 );box-shadow:-5px -2px 5px #111;border-radius:30px 0 5px 15px}
.paw-cat-r{margin:5px 22px;border-radius:0px 30px 15px 5px;background:linear-gradient(rgba(1,1,1,.4) ,rgba(34,34,34,.8)),linear-gradient(90deg,#111,#222 70%,#111);}
.paw-cat-l div,.paw-cat-r div,.tail-cat {border-radius:70px 10px 70px 10px;background:linear-gradient(#111,#222);position:absolute;width:12px;height:7px;top:38px;left:1px}
.paw-cat-r div {border-radius:10px 70px 10px 70px;}
.tail-cat{transform:rotate(-15deg);top:80px;width:40px;height:13px;border-radius:40px;background:linear-gradient(60deg,#111,#222)}

@-webkit-keyframes eye-cat {0%{left:3px} 10%{border-radius:30px} 20%{border-radius:30px;} 30% {left:1px} 40%{top:2px;right:2px} 50%{left:4px} 60%{bottom:2px} 70%{bottom:0px;width:0} 71%{width:5px} 80%{left:2px}97%{bottom:1px;width:0} 100%{bottom:2px}}
@-moz-keyframes eye-cat {0%{left:3px} 10%{border-radius:30px} 20%{border-radius:30px;} 30% {left:1px} 40%{top:2px;right:2px} 50%{left:4px} 60%{bottom:2px} 70%{bottom:0px;width:0} 71%{width:5px} 80%{left:2px}97%{bottom:1px;width:0} 100%{bottom:2px}}
@keyframes eye-cat 
{0%{left:3px} 10%{border-radius:30px} 20%{border-radius:30px;} 30% {left:1px} 40%{top:2px;right:2px} 50%{left:4px} 60%{bottom:2px} 70%{bottom:0px;width:0} 71%{width:5px} 80%{left:2px}97%{bottom:1px;width:0} 100%{bottom:2px}}
@-webkit-keyframes sleep-cat {
  0%{
    height:7px;
    top:12px;
    border-radius:30px;
  } 
  35%{
    height:8px;
    top:12px;
    border-radius:30px;
  } 
  56%{
    height:4px;
    top:13px
  } 
  70%{
    height:3px;
    top:15px
  } 
  71%{
    height:8px;
    top:12px
  } 
  85%{
    height:3px;
    top:12px
  } 
  97%{
    height:0;
    top:15px
  }
}
@-moz-keyframes sleep-cat {0%{height:7px;top:12px;border-radius:30px} 35%{height:8px;top:12px;border-radius:30px} 56%{height:4px;top:13px} 70%{height:3px;top:15px} 71%{height:8px;top:12px} 85%{height:3px;top:12px} 97%{height:0;top:15px}}
@keyframes sleep-cat 
{0%{height:7px;top:12px;border-radius:30px} 35%{height:8px;top:12px;border-radius:30px} 56%{height:4px;top:13px} 70%{height:3px;top:15px} 71%{height:8px;top:12px} 85%{height:3px;top:12px} 97%{height:0;top:15px}}
@-webkit-keyframes jaws-cat {0%{height:0} 50%{height:3px}100%{height:0}}
@-moz-keyframes jaws-cat {0%{height:0} 50%{height:3px}100%{height:0}}
@keyframes jaws-cat {0%{height:0} 50%{height:3px}100%{height:0}}
@-webkit-keyframes star-anim {0% {box-shadow:0 0 10px 2px #fff;}
  100% {box-shadow:0 0 5px 1px #fff;}}
@-moz-keyframes star-anim {0% {box-shadow:0 0 10px 2px #fff;}
  100% {box-shadow:0 0 5px 1px #fff;}}
@keyframes star-anim {0% {box-shadow:0 0 10px 2px #fff;}
  100% {box-shadow:0 0 5px 1px #fff;}}
@-webkit-keyframes moon-anim {0% {box-shadow:0 0 20px 5px #fc0;}
  100% {box-shadow:0 0 10px 5px #fc0;}}
@-moz-keyframes moon-anim {0% {box-shadow:0 0 20px 5px #fc0;}
  100% {box-shadow:0 0 10px 5px #fc0;}}
@keyframes moon-anim {0% {box-shadow:0 0 20px 5px #fc0;}
  100% {box-shadow:0 0 10px 5px #fc0;}}
@-webkit-keyframes satellit-anima {0% {bottom:0;opacity:0} 2%{opacity:1;transform:rotate(130deg)}15%{transform:rotate(120deg);color:#fff;text-shadow:0 0 8px}
35%{font-size:20px;left:15%;transform:rotate(180deg);}55%{font-size:17px;transform:rotate(185deg);color:#ff8;text-shadow:0 0 8px}57%{font-size:14px;} 100% {font-size:10px;transform:rotate(200deg);bottom:90%;left:90%;opacity:0}}
@-moz-keyframes satellit-anima {0% {bottom:0;opacity:0} 2%{opacity:1;transform:rotate(130deg)}15%{transform:rotate(120deg);color:#fff;text-shadow:0 0 8px}
35%{font-size:20px;left:15%;transform:rotate(180deg);}55%{font-size:17px;transform:rotate(185deg);color:#ff8;text-shadow:0 0 8px}57%{font-size:14px;} 100% {font-size:10px;transform:rotate(200deg);bottom:90%;left:90%;opacity:0}}
@keyframes satellit-anima {0% {bottom:0;opacity:0} 2%{opacity:1;transform:rotate(130deg)}15%{transform:rotate(120deg);color:#fff;text-shadow:0 0 8px}
35%{font-size:20px;left:15%;transform:rotate(180deg);}55%{font-size:17px;transform:rotate(185deg);color:#ff8;text-shadow:0 0 8px}57%{font-size:14px;} 100% {font-size:10px;transform:rotate(200deg);bottom:90%;left:90%;opacity:0}}

@-webkit-keyframes meteors{0%{margin:-300px -300px 0 0;opacity:1}8%{opacity:0}
30% {margin-top:300px -600px 0 0;opacity:0}100% {opacity:0}}
@-moz-keyframes meteors{0%{margin:-300px -300px 0 0;opacity:1}8%{opacity:0}
30% {margin-top:300px -600px 0 0;opacity:0}100% {opacity:0}}
@keyframes meteors{0%{margin:-300px -300px 0 0;opacity:1}8%{opacity:0}
30% {margin-top:300px -600px 0 0;opacity:0}100% {opacity:0}}
 /* Styles pour l'image */
 img {
  position: absolute;       /* Position absolue pour l'image */
  top: -640%;                 /* Centre verticalement */
  left: 0;                  /* Aligne à gauche */
  transform: translateY(-50%); /* Ajuste pour centrer verticalement */
  
  max-width: 300px;         /* Largeur maximale de 300 pixels */
  max-height: 300px;        /* Hauteur maximale de 300 pixels */
  width: auto;              /* Conserve les proportions de l'image */
  height: auto;             /* Conserve les proportions de l'image */

  border: 5px solid #3498db; /* Bordure bleue de 5 pixels */
  border-radius: 15px;       /* Bords arrondis */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombrage pour un effet de relief */
}
</style>