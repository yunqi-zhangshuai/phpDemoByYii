
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<style type="text/css">
    .only-box {
        width: 736px;
        height: 670px;
        border: 1px silver solid;
        background-color: white;
        display: flex;
        flex-direction: column;
        margin: 100px auto;
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
</style>

<div class="only-box ">
    <div class="title-box"><p class="title-font">小明</p></div>
    <div class="message_box">消息框</div>
    <div class="edit_box">
        <textarea class="layui-textarea layui-hide" name="description" lay-verify="description" id="edit_box">

        </textarea>
    </div>
    <button class=""></button>
</div>

<script>

    layui.use(['layedit'], function () {

        layui.layedit.build('edit_box', {height: 'auto'});

    });

</script>
</body>
</html>


