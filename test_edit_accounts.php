<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
    $id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
    //TODO add proper validation/checks
    $account_number = $_POST["account_number"];
    $account_type = $_POST["account_type"];
    $balance = $_POST["balance"];
    $user_id = get_user_id();
    $db = getDB();
   /* if(isset($id)){
		$stmt = $db->prepare("UPDATE F20_Eggs set name=:name, state=:state, base_rate=:br, mod_min=:min, mod_max=:max, next_stage_time=:nst where id=:id");
		//$stmt = $db->prepare("INSERT INTO F20_Eggs (name, state, base_rate, mod_min, mod_max, next_stage_time, user_id) VALUES(:name, :state, :br, :min,:max,:nst,:user)");
		$r = $stmt->execute([
			":name"=>$name,
			":state"=>$state,
			":br"=>$br,
			":min"=>$min,
			":max"=>$max,
			":nst"=>$nst,
			":id"=>$id
		]);*/
  

        if($r){
            flash("Updated successfully with id: " . $user_id);
        }
        else{
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else{
        flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Accounts where id = :id");
    $r = $stmt->execute([":id"=>$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
$db = getDB();
$stmt = $db->prepare("SELECT id,Name from Accounts LIMIT 10");
$r = $stmt->execute();
$eggs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST">

/* <label>Name</label>
	<input name="name" placeholder="Name" value="<?php echo $result["name"];?>"/>
	<label>State</label>
	<select name="state" value="<?php echo $result["state"];?>">
		<option value="0" <?php echo ($result["state"] == "0"?'selected="selected"':'');?>>Incubating</option>
                <option value="1" <?php echo ($result["state"] == "1"?'selected="selected"':'');?>>Hatching</option>
                <option value="2" <?php echo ($result["state"] == "2"?'selected="selected"':'');?>>Hatched</option>
                <option value="3" <?php echo ($result["state"] == "3"?'selected="selected"':'');?>>Expired</option>
	</select>
  */


    <label>Account Number</label>
    <input Name="account_number" placeholder="Account Number" value ="<?php echo $result["account_number"];?>"/>
    <label>Account Type</label>
    <select Name="account_type" value="<?php echo $result["account_type"];?>">
        <option value="Checking" <?php echo ($result["account_type"] == "Checking"?'selected="selected"':'');?>>Checking</option>
        <option value="Savings" <?php echo ($result["account_type"] == "Savings"?'selected="selected"':'');?>>Savings</option>
     
    </select>
    <label>Balance</label>
    <input name ="balance" type="number" value="<?php echo $result["balance"];?>"/>
    <input type="submit" name="save" value="Update"/>
</form>

<?php require(__DIR__ . "/partials/flash.php");
