

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="pokedex.css" type="text/css">
    <title>Pokedex</title>
</head>
<body>


<?php
$currentPage=1;

if(isset($_GET['submit'])){

  if($_GET['pokemonId'] !== "" && json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $_GET['pokemonId'])) !== null){
      $currentPage = $_GET['pokemonId'];
  }
}

$currentPokemon = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' .$currentPage ));
$currentPage = $currentPokemon ->id;

$nextPage = $currentPage + 1;
$prevPage = $currentPage - 1;


$speciesData = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . $currentPokemon->id));


function evolutionimages(){
    //getting al evolutions
    global $speciesData;
    $evolutionData= json_decode(file_get_contents($speciesData->evolution_chain->url));

    $baseEvolutionSet = $evolutionData -> chain -> species -> name;
    $firstEvolutionSet = $evolutionData -> chain -> evolves_to[0] ->species -> name;
    $secondEvolutionSet = $evolutionData -> chain -> evolves_to[0] -> evolves_to[0] ->species -> name;

//converting all evolutions to images for display
    $baseEvolution = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $baseEvolutionSet));
    $firstEvolution = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $firstEvolutionSet));
    $secondEvolution = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $secondEvolutionSet));

    $baseEvolutionImage = $baseEvolution -> sprites -> front_default;
    $firstEvolutionImage = $firstEvolution -> sprites -> front_default;
    $secondEvolutionImage = $secondEvolution -> sprites -> front_default;

    echo "<div id='first-pokemon'> <img id='first-evolution' src=$baseEvolutionImage> </div>";
    echo "<div id='second-pokemon'> <img id='second-evolution' src=$firstEvolutionImage> </div>";
    echo "<div id='third-pokemon'> <img id='third-evolution' src=$secondEvolutionImage> </div>";

}

function evolvesfrom(){
global $speciesData;
    if($speciesData->evolves_from_species->name === null){
      echo "No previous evolution";
   }else{
      echo "Evolved from: " . $speciesData ->evolves_from_species->name ;
        }
}


    for($i=0; $i< count($currentPokemon->moves);$i++){
        if($i<3){
            $newArray[] = $currentPokemon->moves[$i]->move->name;
        }
    }
?>


<!-- POKEDEX START -->

<div class="hoennPokedex">
    <div class="rectangle"></div>
    <div class="inner-rectangle"></div>
    <div class="bottom-rectangle"></div>
    <div class="side1"></div>
    <div class="side2"></div>
</div>
<div class="side3"></div>
<div class="side4"></div>
<div class="sound"></div>
<div class="sound2"></div>
<div class="sound3"></div>
<div class="sound4"></div>
<div class="screen-bg">
    <div class="background"></div>
    <div id="screen" class="screen">
        <form method="get" id="userInput">
            <input type="text" id="input" name="pokemonId">
        </form>
        <div class="screen-sides"></div>
        <img id="sprites" src=<?php echo $currentPokemon->sprites->front_default ?> >
        <p> <?php echo $currentPokemon->name ?></p>
        <p> <?php echo "Id: " . $currentPokemon->id ?></p>
        <p> <?php global $newArray; echo "Moves: " . implode(", ",$newArray) ?></p>
        <p> <?php evolvesfrom()  ?></p>
    </div>
    <div class="background-button"></div>
    <div class="pokedex-bg-button"></div>
    <div class="button-line"></div>
    <div class="pokedex-bg-button2"></div>

    <input  id="run" class="pokedex-button" type="submit" name="submit" form="userInput" value="">

    <div class="deco1">
        <div class="deco-button"></div>
    </div>
    <div class="deco2">
        <div class="deco-button"></div>
    </div>

  <?php echo "<a href='http://pokedex.local/?pokemonId=$nextPage&submit=Submit'><div id='next' class='next-button'> </div> </a>"  ?>
  <?php echo "<a href='http://pokedex.local/?pokemonId=$prevPage&submit=Submit'><div id='prev' class='prev-button'> </div> </a>"  ?>
  <div class="switch-button"></div>
  <?php evolutionimages(); ?>

</div>

<!-- POKEDEX END -->

<div class="title">
    <img class="title" src="title.png" alt="pokemon-title">
</div>

</body>
</html>
