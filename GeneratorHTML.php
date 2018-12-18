<?php
class GeneratorHTML{

    public static function printTheAttackedField($attackedField,$pdo,&$body){

        $enemyCells=Controller::getCells($pdo,$attackedField);
        $count2=0;
        $body.='
        <br>
        <table>
        <tr>';
        foreach ($enemyCells as $enemyCell) {
           if($enemyCell->cellCondition==0 || $enemyCell->cellCondition==1){
               $body.='<td><a target="_self" href="index.php?action=moveStep&chosenCell='.(string)$enemyCell->numCell.'">'.'<img src="freeCell.jpg" alt="Not found"/>'.'</a></td>   ';
           }
           if($enemyCell->cellCondition==2){
            $body.='<td>'.'<img src="slip.jpg" alt="Not found"/>'.'</td>';
        }
        if($enemyCell->cellCondition==3){
            $body.='<td>'.'<img src="damageCell.jpg" alt="Not found"/>'.'</td>';
        }

        $count2++;
        if($count2%10==0){
            if($count2==100){
                $body.='</tr>';
            }else{
                $body.='</tr> <tr>';
            }
        }
    }
    $body.='</table>';


}

public static function printTheAttackersField($field,$pdo,&$body){


   $cells=Controller::getCells($pdo,$field);
   $count1=0;
   $body.='
   <br>
   <table>
   <tr> ';
   foreach ($cells as $cell) {
    if($cell->cellCondition==0){
        $body.=' <td>'.'<img src="freeCell.jpg" alt="Not found"/>'.'</td> ';
    }
    if($cell->cellCondition==1){
        $body.=' <td>'.'<img src="busyCell.jpg" alt="Not found"/>'.'</td> ';
    }
    if($cell->cellCondition==2){
        $body.=' <td> '.'<img src="slip.jpg" alt="Not found"/>'.' </td> ';
    }
    if($cell->cellCondition==3){
        $body.=' <td> '.'<img src="damageCell.jpg" alt="Not found"/>'.' </td> ';
    }
    $count1++;
    if($count1%10==0){
        if($count1==100){
            $body.='</tr> ';
        }else{
            $body.='</tr> <tr>';
        }
    }
}
$body.='</table>';
}


public static function fillInTheField(&$head,&$body,&$footer){
    $head='
    <!DOCTYPE html>
    <html>
    <head>
    <title>Морской бой</title>
    <style>
    body{
        color:#8B0000;
        margin: 0;
    }
    </style>
    </head>';

    $body='
    <p>Расставьте корабли и введите имя: </p>
    <form method="GET" id="my_form"></form>
    <table>
    <tr>
    <td> </td>
    <th>А</th>
    <th>Б</th>
    <th>В</th>
    <th>Г</th>
    <th>Д</th>
    <th>Е</th>
    <th>Ж</th>
    <th>З</th>
    <th>И</th>
    <th>К</th>
    </tr>
    <tr>
    <th>1</th>
    <td><input type="checkbox" name="cell[]" value="1" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="2" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="3" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="4" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="5" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="6" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="7" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="8" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="9" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="10" form="my_form"></td>
    </tr>
    <tr>
    <th>2</th>
    <td><input type="checkbox" name="cell[]" value="11" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="12" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="13" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="14" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="15" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="16" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="17" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="18" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="19" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="20" form="my_form"></td>
    </tr>
    <tr>
    <th>3</th>
    <td><input type="checkbox" name="cell[]" value="21" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="22" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="23" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="24" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="25" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="26" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="27" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="28" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="29" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="30" form="my_form"></td>
    </tr>
    <tr>
    <th>4</th>
    <td><input type="checkbox" name="cell[]" value="31" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="32" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="33" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="34" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="35" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="36" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="37" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="38" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="39" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="40" form="my_form"></td>
    </tr>
    <tr>
    <th>5</th>
    <td><input type="checkbox" name="cell[]" value="41" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="42" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="43" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="44" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="45" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="46" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="47" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="48" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="49" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="50" form="my_form"></td>
    </tr>
    <tr>
    <th>6</th>
    <td><input type="checkbox" name="cell[]" value="51" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="52" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="53" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="54" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="55" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="56" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="57" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="58" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="59" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="60" form="my_form"></td>
    </tr>
    <tr>
    <th>7</th>
    <td><input type="checkbox" name="cell[]" value="61" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="62" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="63" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="64" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="65" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="66" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="67" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="68" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="69" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="70" form="my_form"></td>
    </tr>
    <tr>
    <th>8</th>
    <td><input type="checkbox" name="cell[]" value="71" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="72" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="73" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="74" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="75" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="76" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="77" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="78" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="79" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="80" form="my_form"></td>
    </tr>
    <tr>
    <th>9</th>
    <td><input type="checkbox" name="cell[]" value="81" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="82" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="83" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="84" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="85" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="86" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="87" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="88" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="89" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="90" form="my_form"></td>
    </tr>
    <tr>
    <th>10</th>
    <td><input type="checkbox" name="cell[]" value="91" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="92" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="93" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="94" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="95" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="96" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="97" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="98" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="99" form="my_form"></td>
    <td><input type="checkbox" name="cell[]" value="100" form="my_form"></td>
    </tr>
    </table>
    <br>
    <br>
    <input type="text" name="namePlayer" placeholder="Введите имя" form="my_form">
    <br>
    <br>
    <input type="submit" name="action" value="setPlayer" form="my_form">
    <br>
    ';

    $footer='
    </body>
    </html>';
}

public static function createHTML($pdo){
    $countPlayers=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();
    $head='';
    $body='';
    $footer='';
    if($countPlayers<2){
     GeneratorHTML::fillInTheField($head,$body,$footer);
 }else{

    $head='
    <html>
    <head>
    </head>';
    $body='
    <body style="background-image:url(back.jpg);background-repeat:no-repeat;">
    ';
    $footer='
    </body>
    </html>';

    if(Controller::determineWinner($pdo)!='no'){
        $time=date('l jS \of F Y h:i:s A');
        $pdo->exec("UPDATE games SET game_end_time='$time', attacked_field='no' WHERE id=(SELECT MAX(id) FROM games)");
        $body.='<p>Игра окончена!!!</p>';
    }else{


        $attackedField=Controller::determineAttackedField($pdo);

        if($attackedField=='right'){
          GeneratorHTML::printTheAttackersField('left',$pdo,$body);
          GeneratorHTML::printTheAttackedField('right',$pdo,$body);
      }else{
          GeneratorHTML::printTheAttackedField('left',$pdo,$body);
          GeneratorHTML::printTheAttackersField('right',$pdo,$body);
      }
  }
}

echo $head.$body.$footer;
}
} 
?>

