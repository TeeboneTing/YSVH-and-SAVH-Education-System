<div class="span8 offset2">
	<h2>台北榮民總醫院蘇澳暨員山分院</h2>
	<h3>教育訓練課程滿意度調查表</h3>
	<p>各位的意見有助於本院訓練課程的改進。請就以下問題提供您客觀而真實的意見。謝謝您的合作。</p>
	<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
	<?php echo form_open('questionnaire'); //顯示表單頭 ?>
	<h4> <!-- 用query string 填入這些欄位，讓使用者專心填問卷就好 -->
		訓練主題：<?php if(isset($topic)) echo $topic;?> ，
		<input type="text" name="topic_id" value="<?php if(isset($_GET['c_id'])) echo $_GET['c_id']; else echo set_value('topic_id');?>" style="display:none">
		日期：<?php if(isset($datefrom)) echo $datefrom;?> ~ <?php if(isset($dateto)) echo $dateto;?> 
	</h4>
	<table class="table table-striped">
	<tr><td>01：課程主題</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q1" value="5" <?php echo set_radio('Q1', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q1" value="4" <?php echo set_radio('Q1', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q1" value="3" <?php echo set_radio('Q1', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q1" value="2" <?php echo set_radio('Q1', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q1" value="1" <?php echo set_radio('Q1', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>02：教材、講義</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q2" value="5" <?php echo set_radio('Q2', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q2" value="4" <?php echo set_radio('Q2', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q2" value="3" <?php echo set_radio('Q2', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q2" value="2" <?php echo set_radio('Q2', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q2" value="1" <?php echo set_radio('Q2', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>03：教學環境設施</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q3" value="5" <?php echo set_radio('Q3', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q3" value="4" <?php echo set_radio('Q3', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q3" value="3" <?php echo set_radio('Q3', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q3" value="2" <?php echo set_radio('Q3', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q3" value="1" <?php echo set_radio('Q3', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>04：課程進行方式</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q4" value="5" <?php echo set_radio('Q4', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q4" value="4" <?php echo set_radio('Q4', '5');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q4" value="3" <?php echo set_radio('Q4', '5');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q4" value="2" <?php echo set_radio('Q4', '5');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q4" value="1" <?php echo set_radio('Q4', '5');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>05：講師教學技巧</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q5" value="5" <?php echo set_radio('Q5', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q5" value="4" <?php echo set_radio('Q5', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q5" value="3" <?php echo set_radio('Q5', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q5" value="2" <?php echo set_radio('Q5', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q5" value="1" <?php echo set_radio('Q5', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>06：課程內容對個人的適合程度</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q6" value="5" <?php echo set_radio('Q6', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q6" value="4" <?php echo set_radio('Q6', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q6" value="3" <?php echo set_radio('Q6', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q6" value="2" <?php echo set_radio('Q6', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q6" value="1" <?php echo set_radio('Q6', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>07：課程內容對工作的助益度</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q7" value="5" <?php echo set_radio('Q7', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q7" value="4" <?php echo set_radio('Q7', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q7" value="3" <?php echo set_radio('Q7', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q7" value="2" <?php echo set_radio('Q7', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q7" value="1" <?php echo set_radio('Q7', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>08：對課程之綜合評分</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q8" value="5" <?php echo set_radio('Q8', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q8" value="4" <?php echo set_radio('Q8', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q8" value="3" <?php echo set_radio('Q8', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q8" value="2" <?php echo set_radio('Q8', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q8" value="1" <?php echo set_radio('Q8', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>09：對講師之綜合表現評分</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q9" value="5" <?php echo set_radio('Q9', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q9" value="4" <?php echo set_radio('Q9', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q9" value="3" <?php echo set_radio('Q9', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q9" value="2" <?php echo set_radio('Q9', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q9" value="1" <?php echo set_radio('Q9', '1');?> >非常不滿意</label>
		</td>
	</tr>
	<tr><td>10：整體滿意度</td>
		<td>
			<label class="radio inline"><input type="radio" name="Q10" value="5" <?php echo set_radio('Q10', '5');?> >非常滿意</label>
			<label class="radio inline"><input type="radio" name="Q10" value="4" <?php echo set_radio('Q10', '4');?> >滿意</label>
			<label class="radio inline"><input type="radio" name="Q10" value="3" <?php echo set_radio('Q10', '3');?> >普通</label>
			<label class="radio inline"><input type="radio" name="Q10" value="2" <?php echo set_radio('Q10', '2');?> >不滿意</label>
			<label class="radio inline"><input type="radio" name="Q10" value="1" <?php echo set_radio('Q10', '1');?> >非常不滿意</label>
		</td>
	</tr>
	
	<tr>
		<td>11：對本課程之建議</td>
		<td><textarea rows="3" class="input-xlarge" name="Q11"></textarea></td>
	</tr>
	</table>
	<div class="span2 offset3"><input type="submit" value="送出" class="btn btn-primary"> <br></div>
	</form>
</div>