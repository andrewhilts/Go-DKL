<?php
print_r($_POST['claim_goals']);
?>
<form name="myform" action="formtest.php" method="POST">
<select id="claim_goals" name="claim_goals[]"><optgroup label="accessibility"><option style="font-weight: bold;" selected="selected" class="selected alternate" value="poop">activity be accessible</option></optgroup><option style="font-weight: bold;" value="awareness of others activities be supported">awareness of others activities be supported</option><option value="effort be minimal" class="alternate">effort be minimal</option><option value="relevant information be accesible">relevant information be accesible</option><optgroup label="normative"><option value="agreement be sincere" class="alternate">agreement be sincere</option><option value="conflict be reduced">conflict be reduced</option><option value="discussion be ethical" class="alternate">discussion be ethical</option><option value="divergent thinking be facilitated">divergent thinking be facilitated</option><option value="document has diverse viewpoints" class="alternate">document has diverse viewpoints</option><option style="font-weight: bold;" value="foster discussion of differences">foster discussion of differences</option><option style="font-weight: bold;" selected="selected" class="selected alternate" value="neutrality">neutrality</option><option style="font-weight: bold;" value="opinions be diverse">opinions be diverse</option><option style="font-weight: bold;" selected="selected" class="selected alternate" value="opposing views be expressed">opposing views be expressed</option><option value="political efficacy">political efficacy</option><option value="retain democratic nature" class="alternate">retain democratic nature</option><option value="sensitive issues be surfaced">sensitive issues be surfaced</option></optgroup></select>
<input type="submit" value=yo>
</form>