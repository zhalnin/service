<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 03/08/14
 * Time: 18:58
 */
namespace dmn\view\utils;
error_reporting(E_ALL & ~E_NOTICE);

//снимаем фильтр - стартуем скрипт без параметров POST
echo "<a class='link' href=$_SERVER[PHP_SELF]?cmd=Users&page=$_GET[page]>Снять фильтр</a><br />";
?>
<table>
    <tr>
        <td>
            <?php
            // если старт даты не отмечен, отмечаем снятый чек
            if( empty( $_POST['chk_begin'] ) ) $chk_begin = "";
            else $chk_begin = "checked"; // или устанавливаем чек
            // если окончание даты не отмечен, отмечаем снятый чек
            if( empty( $_POST['chk_end'] ) ) $chk_end = "";
            else $chk_end = "checked"; // или устанавливаем чек
//            echo "<tt><pre>". print_r( $_REQUEST, TRUE) . "</pre></tt>";
            ?>
            <form name="form" method="post">
                <table>
                    <tr>
                        <td class="field">Начало:</td>
                        <td class="field">
<!--                            по клику запускаем функцию для активации/деактивации опций селектора -->
                            <input type="checkbox"
                                    name="chk_begin"
                                    onclick="freeze_begin( this.form )"
                                <?php echo htmlspecialchars( $chk_begin ); ?> />
                        </td>
                        <td class="field">
                            <?php
                            // если старт даты не указан, берем текущее время в микросекундах
                            if( empty( $_GET['begin_date'] ) ) $date = time();
                            else $date = $_GET['begin_date']; // если указан, то берем его
                            $date_month = date( "m", $date ); // месяц
                            $date_day   = date( "d", $date ); // день
                            $date_year  = date( "Y", $date ); // год
                            // Выпадающий список для дня
                            echo "<select title='День' class='input' type='text' name='b_date_day' >";
                            for( $i = 1; $i <= 31; $i++ ) {
                                if( $date_day == $i ) $temp = "selected";
                                else $temp = "";
                                echo "<option value=$i $temp>".sprintf("%02d", $i );
                            }
                            echo "</select>";
                            // Выпадающий список для месяца
                            echo "<select title='Месяц' class='input' type='text' name='b_date_month' >";
                            for( $i = 1; $i <= 12; $i++ ) {
                                if( $date_month == $i ) $temp = "selected";
                                else $temp = "";
                                echo "<option value=$i $temp>".sprintf("%02d", $i );
                            }
                            echo "</select>";
                            // Выпадающий список для года
                            echo "<select title='Год' class='input' type='text' name='b_date_year' >";
                            for( $i = 2004; $i <= 2017; $i++ ) {
                                if( $date_year == $i ) $temp = "selected";
                                else $temp = "";
                                echo "<option value=$i $temp>".sprintf( "%02d", $i );
                            }
                            echo "</select>";
                            ?>
                        </td>
                        <td><p class='help'></p></td>
                    </tr>
                    <tr>
                        <td class='field'>Конец:</td>
                        <td class='field'>
<!--                            по клику запускаем функцию для активации/деактивации опций селектора -->
                            <input type="checkbox"
                                   name="chk_end"
                                   onclick="freeze_end( this.form )"
                                   <?php echo htmlspecialchars( $chk_end ); ?> />
                        </td>
                        <td class="field">
                            <?php
                            // если окончание даты не указано, берем текущее время в микросекундах
                            if( empty( $_GET['end_date'] ) ) $date = time();
                            else $date = $_GET['end_date'];
                            $date_month = date( 'm', $date );
                            $date_day   = date( 'd', $date );
                            $date_year  = date( 'Y', $date );
                            // Выпадающий список для дня
                            echo "<select title='День' class='input' type='text' name='e_date_day' >";
                            for( $i = 1; $i <= 31; $i++ ) {
                                if( $date_day == $i ) $temp = "selected";
                                else $temp = "";
                                echo "<option value=$i $temp>".sprintf("%02d", $i );
                            }
                            echo "</select>";
                            // Выпадающий список для месяца
                            echo "<select title='Месяц' class='input' type='text' name='e_date_month' >";
                            for( $i = 1; $i <= 12; $i++ ) {
                                if( $date_month == $i ) $temp = "selected";
                                else $temp = "";
                                echo "<option value=$i $temp>".sprintf("%02d", $i );
                            }
                            echo "</select>";
                            // Выпадающий список для года
                            echo "<select title='Год' class='input' type='text' name='e_date_year' >";
                            for( $i = 2004; $i <= 2017; $i++ ) {
                                if( $date_year == $i ) $temp = "selected";
                                else $temp = "";
                                echo "<option value=$i $temp>".sprintf( "%02d", $i );
                            }
                            echo "</select>";
                            ?>
                        </td>
                        <td><p class='help'></p></td>
                    </tr>
                    <tr>
                        <td class="field"></td>
                        <td class="field"></td>
                        <td class="field">
                            <input type="submit"
                                    class="button"
                                    value="Установить дату" />
                        </td>
                    </tr>
                </table>
            </form>
<!--            для активации и деактивации селекторов в фильтре-->
            <script type="text/javascript">
                <!--
//                деактивация начальной даты
//                если чекбокс снят, то опции селектора деактивированы,
//                если установлен, то опции селекторов активированы
                function freeze_begin( form ) {
                    form.b_date_day.disabled   = !form.chk_begin.checked;
                    form.b_date_month.disabled = !form.chk_begin.checked;
                    form.b_date_year.disabled  = !form.chk_begin.checked;
                }
//                деактивация  даты окончания
//                если чекбокс снят, то опции селектора деактивированы,
//                если установлен, то опции селекторов активированы
                function freeze_end( form ) {
                    form.e_date_day.disabled   = !form.chk_end.checked;
                    form.e_date_month.disabled = !form.chk_end.checked;
                    form.e_date_year.disabled  = !form.chk_end.checked;
                }

//                деактивация начальной даты
//                если передан чекбокс - снят, то опции селектора деактивированы,
//                если установлен, то опции селекторов активированы
                if('<?php $chk_begin; ?>' == 'checked' ) {
                    document.form.b_date_day.disabled   = false;
                    document.form.b_date_month.disabled = false;
                    document.form.b_date_year.disabled  = false;
                } else {
                    document.form.b_date_day.disabled   = true;
                    document.form.b_date_month.disabled = true;
                    document.form.b_date_year.disabled  = true;
                }

//                деактивация  даты окончания
//                если постом передан чекбокс - снят, то опции селектора деактивированы,
//                если установлен, то опции селекторов активированы
                if('<?php $chk_end; ?>' == 'checked' ) {
                    document.form.e_date_day.disaeled   = false;
                    document.form.e_date_month.disabled = false;
                    document.form.e_date_year.disabled  = false;
                } else {
                    document.form.e_date_day.disabled   = true;
                    document.form.e_date_month.disabled = true;
                    document.form.e_date_year.disabled  = true;
                }


                    //-->
            </script>
        </td>
    </tr>
</table>