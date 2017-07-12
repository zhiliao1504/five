<form action="add" method="post">
    <table border="1" width="500px">
        <tr>
            <td>名字</td>
            <td>
                <input type="text" name="name"/>
            </td>
        </tr>
        <tr>
            <td>年龄</td>
            <td>
                <select name="age">
                    <?php for($i=18;$i<120;$i++){?>
                        <option value="<?= $i?>"><?= $i?></option>
                    <?php }?>
                </select>
            </td>
        </tr>
        <tr>
            <td>性别</td>
            <td>
                <input type="radio" name="sex" value="男" checked/>男
                <input type="radio" name="sex" value="女"/>女
            </td>
        </tr>
        <tr>
            <td>爱好</td>
            <td>
                <input type="checkbox" name="hobby[]" value="篮球"/>篮球
                <input type="checkbox" name="hobby[]" value="足球"/>足球
                <input type="checkbox" name="hobby[]" value="乒乓球" checked/>乒乓球
            </td>
        </tr>
        <tr>
            <td>
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            </td>
            <td>
                <input type="submit" value="提交"/>
            </td>
        </tr>
    </table>
</form>