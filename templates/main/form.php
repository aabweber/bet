<?php
/**
 * Created by PhpStorm.
 * User: aabweber
 * Date: 10/09/2019
 * Time: 09:58
 */

?>
<!--Сама форма для ставки-->
<a href="#makeBet" role="button" class="btn" data-toggle="modal">Сделать ставку</a>

<div id="makeBet" class="modal hide fade text-center" tabindex="-1" role="dialog" aria-labelledby="modal-area" aria-hidden="true">
    <div class="modal-header">
        <h3 id="makeBetLabel">Ставка на спорт</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <form id="betForm" method="post">
            <label for="#value">Ставка:</label>
            <input type="text" placeholder="Ставка от 100 до 1000" id="value"/>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
        <button class="btn btn-primary">Создать</button>
    </div>
</div>
