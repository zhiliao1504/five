<form action="upd" method="post">
    <table border="1" width="400px">
        <tr>
            <td>名字</td>
            <td><input type="text" name="name" value="<?= $data['name']?>"/></td>
        </tr>
        <tr>
            <td>年龄</td>
            <td>

                <select name="age">
                    <option value="<?= $data['age']?>"><?= $data['age']?></option>
                    <?php for($i=18;$i<120;$i++){?>
                        <option value="<?= $i?>"><?= $i?></option>
                    <?php }?>
                </select>
            </td>
        </tr>
        <tr>
            <td>性别</td>
            <td>
                <?php if($data['sex']=='男'){?>
                    <input type="radio" name="sex" value="男" checked>男
                    <input type="radio" name="sex" value="女"/>女
                <?php }else{?>
                    <input type="radio" name="sex" value="男" >男
                    <input type="radio" name="sex" value="女" checked/>女
                <?php }?>
            </td>
        </tr>
        <tr>
            <td>爱好</td>
            <td>
                <?php if(in_array('篮球',$data['hobby'])){?>
                    <input type="checkbox" name="hobby[]" value="篮球" checked/>篮球
                <?php }else{?>
                    <input type="checkbox" name="hobby[]" value="篮球"/>篮球
                <?php }?>
                <?php if(in_array('足球',$data['hobby'])){?>
                    <input type="checkbox" name="hobby[]" value="足球" checked/>足球
                <?php }else{?>
                    <input type="checkbox" name="hobby[]" value="足球"/>足球
                <?php }?>
                <?php if(in_array('乒乓球',$data['hobby'])){?>
                    <input type="checkbox" name="hobby[]" value="乒乓球" checked>乒乓球
                <?php }else{?>
                    <input type="checkbox" name="hobby[]" value="乒乓球">乒乓球
                <?php }?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="hidden" value="<?= $data['id']?>" name="id"/>
            </td>
            <td>
                <input type="submit" value="修改"/>
            </td>
        </tr>
    </table>
</form>