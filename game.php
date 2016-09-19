<?php
session_start();
if (!isset($_SESSION["userId"])) {
    header("Location: http://daniellesko.com/click_v2/");
}
?>
<html>
    <head>
        <title>Dunglicker</title> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <!-- jQuery UI -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
        <!-- Custom css -->
        <link rel="stylesheet" href="css/game.css">
    </head>
    <body>
        <div class="container">
            <button class="btn btn-default" id="btnReawaken" <?php print ( ($currentRound > 50) ? 'class="hidden"' : '' ); ?> >ASCEND BOYS</button>
            <div id="game_window">
                <div class="row">
                    <table id="game_stats" class="col-md-4 table table-bordered table-hover" style="border: transparent; width: 30%;">
                        <tbody>
                            <tr>
                                <th>Game Round</th>
                                <td id="gameRound"></td>
                            </tr>
                            <tr>
                                <th scope="">Game Subround</th>
                                <td id="gameSubRound"></td>
                            </tr>
                            <tr>
                                <th scope="">Gold</th>
                                <td id="gameGold"></td>
                            </tr>
                            <tr>
                                <th scope="">Emeralds</th>
                                <td id="gameEmeralds"></td>
                            </tr>
                            <tr>
                                <th scope="">Experience</th>
                                <td id="gameExperience"></td>
                            </tr>
                            <tr>
                                <th scope="">Damage</th>
                                <td id="gameDamage"></td>
                                <td id="btnGameDamage" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="">Attack Speed</th>
                                <td id="gameAttackSpeed"></td>
                                <td id="btnGameAttackSpeed" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title=""><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></td>
                            </tr>
                            <tr>
                                <th scope="">Critical Chance</th>
                                <td id="gameCriticalHitChance"></td>
                                <td id="btnGameCriticalHitChance" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title=""><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></td>
                            </tr>
                            <tr>
                                <th scope="">Critical Damage</th>
                                <td id="gameCriticalHitDamage"></td>
                                <td id="btnGameCriticalHitDamage" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title=""><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></td>
                            </tr>
                            <tr>
                                <th scope="">Energy</th>
                                <td id="gameEnergy"></td>
                                <td id="btnGameEnergy" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title=""><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></td>
                            </tr>
                            <tr>
                                <th scope="">Energy Regen</th>
                                <td id="gameEnergyRegen"></td>
                                <td id="btnGameEnergyRegen" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!--                <div id="game_options">
                                        <button class="col-md-1 col-md-offset-9" id="btn_options"><span class="glyphicon glyphicon-cog" style="font-size: 30px;"></span></button>
                                    </div>-->
                    <div id="gameMonster" class="col-md-5 col-md-offset-1">
                        <img id= "gameMonsterImg" class="img-responsive" alt="">
                        <h1 id="gameMonsterHealth"></h1>
                        <div id="healthSlider" class="col-md-12"></div>
                        <div id="timeSlider" class="col-md-12"></div>
                    </div>
                    <table id="tableSpells" class="col-md-offset-10">
                        <tbody>
                            <tr>
                                <td id="spell1" class="spellCell" data-toggle="tooltip" data-placement="left" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
                                </td>
                                <td><p id="spellCooldown1"></p></td>
                                <td id="spellUpgrade1" class="spellUpgrade" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <!--<button class="btn btn-default spellUpgrade" id="spellUpgrade1">Apgrejd</button>-->
                                </td>
                                
                            </tr>
                            <tr>
                                <td id="spell2" class="spellCell">
                                    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
                                </td>
                                <td><p id="spellCooldown2"></p></td>
                                <td id="spellUpgrade2" class="spellUpgrade" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <!--<button class="btn btn-default spellUpgrade" id="spellUpgrade2">Apgrejd</button>-->
                                </td>
                                
                            </tr>
                            <tr>
                                <td id="spell3" class="spellCell">
                                    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
                                </td>
                                <td><p id="spellCooldown3"></p></td>
                                <td  id="spellUpgrade3" class="spellUpgrade" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span>
                                    <!--<button class="btn btn-default spellUpgrade" id="spellUpgrade3">Apgrejd</button>-->
                                </td>
                                
                            </tr>
                            <tr>
                                <td id="spell6" class="spellCell">
                                    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
                                </td>
                                <td><p id="spellCooldown6"></p></td>
                                <td id="spellUpgrade6" class="spellUpgrade" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <!--<button class="btn btn-default spellUpgrade" id="spellUpgrade6">Apgrejd</button>-->
                                </td>
                                
                            </tr>
                            <tr>
                                <td id="spell7" class="spellCell">
                                    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
                                </td>
                                <td><p id="spellCooldown7"></p></td>
                                <td id="spellUpgrade7" class="spellUpgrade" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <!--<button class="btn btn-default spellUpgrade" id="spellUpgrade7">Apgrejd</button>-->
                                </td>
                                
                            </tr>
                            <tr>
                                <td id="spell8" class="spellCell">
                                    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
                                </td>
                                <td><p id="spellCooldown8"></p></td>
                                <td id="spellUpgrade8" class="spellUpgrade" data-toggle="tooltip" data-placement="right" data-animation="container: 'body'" title="">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <!--<button class="btn btn-default spellUpgrade" id="spellUpgrade8">Apgrejd</button>-->
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row" style="margin-top: 35px;">
                    <div>
                        <div id="tableContainer" class="col-md-3"></div>
                        <div id="itemDetailsContainer" class="col-md-5 col-md-offset-1"></div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="js/numeral.min.js"></script>
        <script src="js/game.js"></script>
    </body>
</html>
