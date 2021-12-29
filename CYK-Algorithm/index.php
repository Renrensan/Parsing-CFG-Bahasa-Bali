<?php 
include_once("functions.php");
include_once("CFG.php");
include_once("CYKGenerator.php");

if( isset($_POST['submit']) ){
    if( isset($_POST['stcInput']) && !empty($_POST['stcInput']) ){

        $sentence = $_POST['stcInput'];
        $cfg = new CFG(get_rules("./assets/rules.txt"), "K"); 
        $cyk = new CYKGenerator($cfg); 
        $cyk = $cyk->generate_table($sentence);
        $cyk = $cyk->solve();

        if( $cyk->validation() ){
            $message =  "The sentence is valid";
        }else{
            $message = "Invalid sentence";
        }
    }
}

if( isset($_POST['files']) ){
    if( isset($_FILES['fileTest'])  && isset($_FILES['fileValid'])){
        $status = [];
        $tests = read_file("./doc/$fileTest");
        $cfg = new CFG(get_rules("./assets/rules.txt"), "K"); 
        $cyk = new CYKGenerator($cfg); 
        foreach( $tests as $test ){
            $test = trim($test, ".");
            
            $cyk = $cyk->generate_table($test);
            $cyk = $cyk->solve();
            if( $cyk->validation() ){
                $status[] =  "valid";
            }else{
                $status[] = "invalid";
            }
        }

        $benar = 0;
        $valids = read_file("./doc/$fileValid");
        foreach( $valids as $pos => $valid ){
            $valid = explode(".", $valid);
            $validStatus = trim(end($valid));
            $validStatus = strtolower(trim(explode(" ", $validStatus)[0]));
            if( $validStatus == 'tidak'){
                $validStatus = "invalid";
            }else{
                $validStatus = "valid";
            }

            if( $status[$pos] == $validStatus ){
                $benar++;
            }
        }

        $akurasi = ($benar/count($valids)) * 100;
        unlink("./doc/$fileTest");
        unlink("./doc/$fileValid");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kalimat Bahasa Bali</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="parent container-fluid d-flex justify-content-center align-items-center h-100">
        <div class="fieldcontainer container-fluid mt-5 d-flex border rounded">
            <div class="search-bar container d-flex justify-content-center align-items-center">
                <div class="row form">
                    <form action="./index.php" method="POST">
                        <div class="row">
                            <input placeholder="Masukkan Kalimat Bahasa Bali di Sini" type="text" class="sentence rounded" name="stcInput" value="<?php if( isset($sentence) ) echo $sentence;?>">
                        </div>
                        <div class="row">
                            <button type="submit" name="submit" class="btn btn-primary mt-3">Check</button>
                        </div>
                        <div class="row section2">
                            <?php if( isset($sentence) ) : ?>
                                <h3 class="<?php echo $color; ?> text-center mt-3"><?php echo $message; ?>.</h3>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>
</html>