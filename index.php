
<?php include('./inc/klantenportaal_header.php'); ?>
<div class="container">
        <div class="blok1">        
                <div class="div1"><img src="./img/klantenportaal1.jpg" alt=""></div>
                <div class="div2"><img src="./img/klantenportaal2.jpg" alt=""> </div>
        </div>
        <div class="blok2">        
                <div class="div3"><h1>Verbonden achter de muren: Samen bouwen aan een nieuwe start</h1> </div>
        </div>
        <div class="blok3">
                <div class="div4"><img src="./img/klantenportaalvrouw.png" alt=""> 
               <div class="text1">Welkom op het online klantenportaal van het Arrestantencomplex te Houten! We zijn verheugd dat u de tijd neemt om meer te weten te komen over ons complex </div></div>
       
    
                <div class="div5">
                <div class="text2">Dus kom binnen, kijk rond en ontdek wat het Arrestantencomplex te bieden heeft. We heten u van harte welkom en staan klaar om u te ondersteunen bij elke stap van uw reis. </div>
               <img src="./img/klantenportaal4.jpg" alt=""></div>
       </div>
       <div class="blok5">
                <div class="div8"><img id="image1" src="./img/klantenportaalmorphing.svg" alt="">
                <img  id="image2" src="./img/klantenportaalmorphing1.png" alt="">
                </div>
                <div class="div9"> Ons complex is meer dan alleen een plaats van detentie; het is een ruimte van hoop, waar gedetineerden de kans krijgen om te groeien, te leren en een nieuwe start te maken. Met toegewijd personeel en moderne faciliteiten streven we ernaar om een positieve impact te hebben op het leven van degenen die hier verblijven.</div>
        </div>
        <script>
        // Initially hide image1 and show image2
        document.getElementById('image1').style.opacity = '0';
        document.getElementById('image2').style.opacity = '1';

        // Function to toggle the opacity of the images
        function toggleImages() {
        const image1 = document.getElementById('image1');
        const image2 = document.getElementById('image2');

        // Toggle the opacity of the images
        if (image1.style.opacity === '1') {
                image1.style.opacity = '0';
                image2.style.opacity = '1';
        } else {
                image1.style.opacity = '1';
                image2.style.opacity = '0';
        }
        }

        // Set interval to switch images every 2 seconds
        setInterval(toggleImages, 2000);

    </script>
        
</div>
<?php include('./inc/klantenportaal_footer.php'); ?>
