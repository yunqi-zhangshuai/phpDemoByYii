<?php
use yii\helpers\Url;
?>


<style type="text/css">
    .only-box {
        width: 736px;
        height: 670px;
        border: 1px silver solid;
        background-color: white;
        display: flex;
        flex-direction: column;
        margin: 100px auto;
        position: relative;
    }

    .title-box {
        height: 78px;
        border-bottom: 1px silver solid;
        /*background-color: rgb(21, 143, 238);*/

    }

    .message_box {
        height: 362px;
        border-bottom: 1px silver solid;
    }

    #edit_box {
        flex: 1;
    }

    .title-font {
        font-size: 24px;
        font-weight: bold;
        line-height: 78px;
        margin-left: 1%;
    }

    .submit {
        width: 80px;
        height: 32px;
        position: absolute;
        right: 15px;
        display: none;
        font-weight: bold;
        font-size: 16px;
    }
</style>
<div class="only-box ">
    <div class="title-box"><p class="title-font">小明</p></div>
    <div class="message_box">消息框</div>
    <div class="edit_box">
        <textarea class="layui-textarea layui-hide" name="description" lay-verify="description" id="edit_box">

        </textarea>
        <button class="layui-btn layui-btn-normal layui-btn-sm submit">ENTER</button>
    </div>
</div>

<script>

    layui.use(['layedit'], function () {
        var lauiedit = layui.layedit;
        edit_obj = lauiedit.build('edit_box', {height: 'auto'});
        $('.submit').show();



        $('.submit').on('click',function () {
          var content = lauiedit.getContent(edit_obj),
              socket  = new WebSocket('<?= Url::to(['message/websocket'],true)?>');
              //
              socket.onopen(Event,function () {

              })

        })

    });

</script>
