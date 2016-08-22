<div class="pageContent">
	<form method="post" action="/configs/edit/<?php echo $this->request->data['Config']['id']?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone)">
		<div class="pageFormContent " layoutH="58">
			<div class="unit">
                <label for="email">配置名称：</label>
                <input type="text" id="name" name="data[Config][name]" class="required" value="<?php echo $this->request->data['Config']['name']?>" />
                <span class="inputInfo"></span>
            </div>
            <div class="unit">
                <label for="email">配置名称(英文)：</label>
                <input type="text" id="type" name="data[Config][type]" class="required" value="<?php echo $this->request->data['Config']['type']?>"/>
                <span class="inputInfo"></span>
            </div>
            <div class="unit">
                <label for="email">配置值：</label>
                <textarea id="value" name="data[Config][value]" class="required textInput" style="margin: 0px;height: 80px;width: 370px;"><?php echo $this->request->data['Config']['value']?></textarea>
                <span class="inputInfo"></span>
            </div>
            <?php if($isAdmin):?>
            <div class="unit">
                <label for="email">配置类型：</label>
                <select name="data[Config][access]" class="combox">
                    <option value="1" <?php if($this->request->data['Config']['access'] == 1):?>selected="selected"<?php endif;?>>用户配置</option>
                    <option value="2" <?php if($this->request->data['Config']['access'] == 2):?>selected="selected"<?php endif;?>>系统配置</option>
                </select>
                <span class="inputInfo"></span>
            </div>
            <?php endif;?>
		</div>
		<div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
	</form>
</div>