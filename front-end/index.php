<?php
    $id_arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30');
    if (!isset($_GET['id'])) {
        echo '<script>window.location = "./index.php?id=" + localStorage.id;</script>';
        die();
    } else if (!in_array($_GET['id'], $id_arr) && !in_array(strtolower($_GET['id']), $id_arr)) {
        echo '<script>delete localStorage.id; alert("id无效！");</script>';
    }

    if (isset($_POST['idString']) and in_array($_POST['idString'], $id_arr)) {
        $id = $_POST['idString'];
        $cur_label = $_POST['labelString'];
        $img = $_POST['imgSrc'];

        $database = mysql_connect("localhost", "root", "struct123")
        or die("无法连接到数据库，请联系   jet@pku.edu.cn. err1");

        mysql_select_db("struct304", $database)
        or die("无法选择数据库，请联系   jet@pku.edu.cn. err2");
        $query = "SELECT * FROM image WHERE IMAGE='$img'";
        $result = mysql_query($query, $database)
        or die("无法访问数据库-2，请联系jet@pku.edu.cn. err3");
        if (mysql_num_rows($result) == 0) {
            die("数据库错误，找不到项目" . $img . "，请联系jet@pku.edu.cn. err4");
        }
        $row = mysql_fetch_array($result);
        $label_cnt = ((int) $row['LABEL_CNT']) + 1;
        $label = substr($row['LABEL'], 0, strlen($row['LABEL']) - 1);
        if ($label_cnt > 1)
            $label = $label . ",";
        $label = $label . $cur_label . "]";
        
        $query = "UPDATE image SET LABEL='$label', LABEL_CNT='$label_cnt' WHERE IMAGE='$img';";
        mysql_query($query, $database)
        or die("无法访问数据库-3，请联系jet@pku.edu.cn. err5" . $label);
    }
?>
<html>
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="action.js"></script>
    <title>快乐标数据</title>
</head>
<body>

<div id="bigDiv">
    <div id="workshopDiv">
        <img id="baseImg" src=
        <?php
            $database = mysql_connect("localhost", "root", "struct123")
            or die("无法连接到数据库，请联系   jet@pku.edu.cn. err1");
            mysql_select_db("struct304", $database)
            or die("无法选择数据库，请联系   jet@pku.edu.cn. err2");
            $query = "SELECT * FROM image ORDER BY LABEL_CNT ASC;";
            $result = mysql_query($query, $database)
            or die("无法访问数据库-2，请联系jet@pku.edu.cn.");
            if (mysql_num_rows($result) == 0) {
                die("数据库错误，找不到项目" . $img . "，请联系jet@pku.edu.cn");
            }
            $flag = false;
            while ($row = mysql_fetch_array($result)) {
                $flag = true;
                break;
            }

            if ($flag)
                echo "./data/" . $row['IMAGE'];
            else 
                echo "./data/done.png/>"
        ?>
        />
        <canvas id="workshop"></canvas>
    </div>
    <div id="toolsDiv" style="display: none;">
        <input type="radio" class="toolSel" name="toolType" value="0" checked/>&nbsp点工具 <br/>
        <input type="radio" class="toolSel" name="toolType" value="1"/>&nbsp圆工具（对径点）<br/>
        <input type="radio" class="toolSel" name="toolType" value="2"/>&nbsp圆工具（圆心-圆周）<br/><br/>
        <button id="revBtn">撤销</button>&nbsp<button id="resetBtn">重置</button><br/>
        <button id="skipBtn">跳过</button>&nbsp<button id="finBtn">完成</button>
        <br/>
        <p>当前已标点数：</p>
        <p id="pointNum">0</p>
    </div>
    <form id="dataForm" method="post">
        <input type="text" id="imgSrcBox" name="imgSrc">
        <input type="text" id="labelBox" name="labelString">
        <input type="text" id="idBox" name="idString">
    </form>

</div>

</body>
</html>