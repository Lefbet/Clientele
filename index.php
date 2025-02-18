<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="pelates.css">
		<title>Εταιρεία</title>
		<style>.error {color: #FF0000;}</style>
		<script type="text/javascript">
			// Ελέγχει τα υποχρεωτικά πεδία στην προσθήκη νέου πελάτη
			function validate_customer() {
				var error = "";
				
				var surname = document.getElementById("epwnymo");
				if (surname.value == "") {
					error = "Το Επώνυμο είναι υποχρεωτικό.";
					document.getElementById("error_para").innerHTML = error;
					return false;
				}
				var name = document.getElementById("onoma");
				if (name.value == "") {
					error = "Το Όνομα είναι υποχρεωτικό.";
					document.getElementById("error_para").innerHTML = error;
					return false;
				} else {
					return true;
				}
			} //validate_customer()
			
			// Ελέγχει τα υποχρεωτικά πεδία στην προσθήκη νέας παραγγελίας/προσφοράς
			function validate_entries() {
				var error = "";
				
				var date = document.getElementById("hmeromhnia");
				if (date.value == "") {
					error = "Η Ημερομηνία είναι υποχρεωτική.";
					document.getElementById("error_para").innerHTML = error;
					return false;
				}
				var description = document.getElementById("perigrafh");
				if (description.value == "") {
					error = "Η Περιγραφή είναι υποχρεωτική.";
					document.getElementById("error_para").innerHTML = error;
					return false;
				}
				var price = document.getElementById("timh");
				if (price.value == "") {
					error = "Η Τιμή είναι υποχρεωτική.";
					document.getElementById("error_para").innerHTML = error;
					return false;
				} else {
					return true;
				}
			} //validate_entries()
			
			function validate_date() {
				var error = "";
				
				var date1 = document.getElementById("hmeromhnia1");
				var date2 = document.getElementById("hmeromhnia2");
				if (date1.value == "" || date2.value == "") {
					error = "Εισάγετε ημερομηνίες.";
					document.getElementById("error_para").innerHTML = error;
					return false;
				} else if (date1.value > date2.value) {
					error = "Η δεύτερη ημερομηνία πρέπει να είναι μεταγενέστερη της πρώτης.";
					document.getElementById("error_para").innerHTML = error;
					return false;
				} else {
					return true;
				}
			} //validate_date()
			
			function hide_show() {
				var x = document.getElementById("prosfores");
				if (x.style.display === "none") {
					x.style.display = "block";
				} else {
					x.style.display = "none";
				}
			} //hide_show()
			
			function confirmation() {
				return confirm("Είστε σίγουροι για την διαγραφή;");
			} //confirmation()
			
			function delete_customer_confirmation() {
				return confirm("ΠΡΟΣΟΧΗ!!! Αυτή η ενέργεια δεν μπορεί να αναιρεθεί! Είστε σίγουροι για την διαγραφή του πελάτη;");
			} //delete_customer_confirmation()
			
			function delete_all_confirmation() {
				return confirm("ΠΡΟΣΟΧΗ!!! Αυτή η ενέργεια δεν μπορεί να αναιρεθεί! Είστε σίγουροι για την διαγραφή όλων των παραγγελιών;");
			} //delete_all_confirmation()
		</script>
	</head>

	<body>
		<?php
			function validate_data($connection,$data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = strip_tags($data);
				$data = htmlspecialchars($data);
				$data = mysqli_real_escape_string($connection,$data);
				return $data;    
			} //validate_data
			
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "magazi";

			// Σύνδεση με την βάση
			$conn = new mysqli($servername, $username, $password, $dbname);

			// Έλεγχος σύνδεσης
			if ($conn -> connect_error) die("Connection failed: " . $conn -> connect_error);
			
			mysqli_set_charset($conn, "utf8");
			
			// Διαδρομές φακέλων
			$source_dir = "C:\\Users\betso\Desktop\Scan\\";
			$dump_dir = "C:\\Users\betso\Desktop\Scan\dump\\";
			$customer_dir = "C:\\Users\betso\Documents\Company\Πελάτες\\";
			$supplier_dir = "C:\\Users\betso\Documents\Company\Προμηθευτές\\";
			$customer_backup_dir = "F:\\Users\betso\Documents\Company\Πελάτες\\";
			$supplier_backup_dir = "F:\\Users\betso\Documents\Company\Προμηθευτές\\";
			$database_backup1_dir = "C:\\Users\betso\Documents\\";
			$database_backup2_dir = "F:\\Users\betso\Documents\\";
			
			// Τα κουμπιά που θα είναι μονίμως εμφανή
			echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
				<input type='submit' class='button' name='customers_button' value='Πελάτες'>
				<input type='submit' class='button' name='suppliers_button' value='Προμηθευτές'>
				<input type='submit' class='button' name='traffic_button' value='Κινήσεις'>
				<input type='submit' class='button' name='total_debt_button' value='Συνολική Οφειλή'>
				<input type='submit' class='button' name='backup_button' value='Backup'>
				</form> ");
			
			/*// Κουμπί συγχρονισμού χρεών όλων των πελατών (εμφάνιση κατά περίπτωση)
			echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
				<input type='submit' class='button' name='sync_button' value='Συγχρονισμός'>
				</form> ");*/
			
			// Πάτημα κουμπιού "Πελάτες"
			if (isset($_POST['customers_button'])) {
				echo("<h3> Αναζήτηση πελατών: </h3>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='search' name='search_input'>
					<input type='submit' class='customer_button' name='search_button' value='Υποβολή'>
					<input type='checkbox' name='search_all'> Αναζήτηση παντού<br/>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='submit' class='customer_button' name='new_customer_button' value='Νέος Πελάτης'>
					</form>");
				
				// Εμφάνιση όλων των εγγραφών στον πίνακα Customers (ταξινόμηση με βάση το επώνυμο)
				$sql = "SELECT * FROM Customers ORDER BY surname ASC";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					echo("<table id='customers_table'>
						<caption><h3> Πελάτες </h3></caption>
						<tr>
						<th></th>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th>Εταιρεία</th>
						<th>Email</th>
						<th>Τηλέφωνο 1</th>
						<th>Τηλέφωνο 2</th>
						<th>Σχόλια</th>
						<th>Οφειλή</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='submit' class='customer_button' name='customer_tab' value='Καρτέλα'>
							</form></td>
							<td>" . $row['surname'] . "</td><td>" . $row['name'] . "</td><td>" . $row['company_name'] . "</td><td>" . $row['email'] . "</td>
							<td>" . $row['telephone1'] . "</td><td>" . $row['telephone2'] . "</td><td>" . $row['comments'] . "</td><td>" . $row['debt'] . "</td>
							</tr>");
					}
					echo("</table><br/>");
				} else echo("<br/>Δεν βρέθηκαν εγγραφές Πελατών.<br/>");
			} //Κουμπί "Πελάτες"
			
			// Πάτημα κουμπιού "Προμηθευτές"
			if (isset($_POST['suppliers_button'])) {
				echo("<h3> Αναζήτηση προμηθευτών: </h3>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='search' name='search_input'>
					<input type='submit' class='supplier_button' name='search_button' value='Υποβολή'>
					<input type='checkbox' name='search_all'> Αναζήτηση παντού<br/>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='submit' class='supplier_button' name='new_supplier_button' value='Νέος Προμηθευτής'>
					</form>");
				
				// Εμφάνιση όλων των εγγραφών στον πίνακα Suppliers (ταξινόμηση με βάση την επωνυμία)
				$sql = "SELECT * FROM Suppliers ORDER BY company_name ASC";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					echo("<table id='suppliers_table'>
						<caption><h3> Προμηθευτές </h3></caption>
						<tr>
						<th></th>
						<th>Επωνυμία</th>
						<th>Email</th>
						<th>Τηλέφωνο 1</th>
						<th>Τηλέφωνο 2</th>
						<th>Λογαριασμοί</th>
						<th>Σχόλια</th>
						<th>Οφειλή</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='supplier_field' value='" . $row['supplier_id'] . "'>
							<input type='submit' class='supplier_button' name='supplier_tab' value='Καρτέλα'>
							</form></td>
							<td>" . $row['company_name'] . "</td><td>" . $row['email'] . "</td><td>" . $row['telephone1'] . "</td><td>" . $row['telephone2'] . "</td>
							<td>" . $row['bank_accounts'] . "</td><td>" . $row['comments'] . "</td><td>" . $row['self_debt'] . "</td>
							</tr>");
					}
					echo("</table><br/>");
				} else echo("<br/>Δεν βρέθηκαν εγγραφές Προμηθευτών.<br/>");
			} //Κουμπί "Προμηθευτές"
			
			// Πάτημα κουμπιού "Κινήσεις"
			if (isset($_POST['traffic_button'])) {
				echo("<h3> Αναζήτηση κινήσεων: </h3>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return validate_date()'>
					<input type='checkbox' name='orders' checked> Παραγγελίες
					<input type='checkbox' name='offers'> Προσφορές
					<input type='checkbox' name='purchases'> Αγορές<br/>
					Από <input type='date' name='date1' id='hmeromhnia1'>
					έως <input type='date' name='date2' id='hmeromhnia2'>
					<input type='submit' class='customer_button' name='search_traffic' value='Υποβολή'>
					</form>");
				echo("<p class='error' id='error_para'></p>");
			} //Κουμπί "Κινήσεις"
			
			// Πάτημα κουμπιού "Υποβολή" αναζήτησης κινήσεων
			if (isset($_POST['search_traffic'])) {
				$search_date1 = validate_data($conn,$_POST['date1']);
				$search_date2 = validate_data($conn,$_POST['date2']);
				
				echo("<h3> Αναζήτηση κινήσεων: </h3>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return validate_date()'>
					<input type='checkbox' name='orders' checked> Παραγγελίες
					<input type='checkbox' name='offers'> Προσφορές
					<input type='checkbox' name='purchases'> Αγορές<br/>
					Από <input type='date' name='date1' id='hmeromhnia1'>
					έως <input type='date' name='date2' id='hmeromhnia2'>
					<input type='submit' class='customer_button' name='search_traffic' value='Υποβολή'>
					</form>");
				echo("<p class='error' id='error_para'></p>");
				
				// Αναζήτηση ημερομηνιών στον πίνακα Customer_orders (ταξινόμηση με βάση την ημερομηνία)
				if (isset($_POST['orders'])) {
					$sql1 = "SELECT * FROM Customer_orders WHERE order_date BETWEEN '" . $search_date1 . "' AND '" . $search_date2 . "' ORDER BY order_date ASC";
					$result1 = $conn -> query($sql1);
					if ($result1 -> num_rows > 0) {
						echo("<table id='search_orders_table'>
							<caption><h3> Παραγγελίες </h3></caption>
							<tr>
							<th>Ημερομηνία</th>
							<th>Επώνυμο</th>
							<th>Όνομα</th>
							<th>Περιγραφή</th>
							<th>Τιμή</th>
							<th>Πληρωμένο Ποσό</th>
							<th>Αρχείο</th>
							</tr>");
						while ($row1 = $result1 -> fetch_assoc()) {
							$res1 = $conn -> query("SELECT * FROM Customers WHERE customer_id = '" . $row1['customer_id'] . "'");
							$a = $res1 -> fetch_assoc();
							echo("<tr>
								<td>" . $row1['order_date'] . "</td><td>" . $a['surname'] . "</td><td>" . $a['name'] . "</td>
								<td>" . $row1['description'] . "</td><td>" . $row1['price'] . "</td>
								<td>" . $row1['paid'] . "</td><td>" . $row1['order_link'] . "</td>");
							echo("<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $row1['customer_id'] . "'>
								<input type='hidden' name='order_date' value='" . $row1['order_date'] . "'>
								<input type='hidden' name='description' value='" . $row1['description'] . "'>
								<input type='hidden' name='price' value='" . $row1['price'] . "'>
								<input type='hidden' name='paid' value='" . $row1['paid'] . "'>
								<input type='submit' class='customer_button' name='edit_order_button' value='Διόρθωση'>
								</form></td>
								<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $row1['customer_id'] . "'>
								<input type='submit' class='customer_button' name='customer_tab' value='Προβολή'>
								</form></td>
								</tr>");
						}
						echo("</table><br/>");
					} else echo("<br/>Δεν βρέθηκαν εγγραφές Παραγγελιών.<br/>");
				}
				
				// Αναζήτηση ημερομηνιών στον πίνακα Customer_offers (ταξινόμηση με βάση την ημερομηνία)
				if (isset($_POST['offers'])) {
					$sql2 = "SELECT * FROM Customer_offers WHERE offer_date BETWEEN '" . $search_date1 . "' AND '" . $search_date2 . "' ORDER BY offer_date ASC";
					$result2 = $conn -> query($sql2);
					if ($result2 -> num_rows > 0) {
						echo("<table id='search_offers_table'>
							<caption><h3> Προσφορές </h3></caption>
							<tr>
							<th>Ημερομηνία</th>
							<th>Επώνυμο</th>
							<th>Όνομα</th>
							<th>Περιγραφή</th>
							<th>Τιμή</th>
							<th>Αρχείο</th>
							</tr>");
						while ($row2 = $result2 -> fetch_assoc()) {
							$res2 = $conn -> query("SELECT * FROM Customers WHERE customer_id = '" . $row2['customer_id'] . "'");
							$b = $res2 -> fetch_assoc();
							echo("<tr><td>" . $row2['offer_date'] . "</td><td>" . $b['surname'] . "</td><td>" . $b['name'] . "</td>
								<td>" . $row2['description'] . "</td><td>" . $row2['price'] . "</td><td>" . $row2['offer_link'] . "</td>");
							echo("<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $row2['customer_id'] . "'>
								<input type='hidden' name='offer_date' value='" . $row2['offer_date'] . "'>
								<input type='hidden' name='description' value='" . $row2['description'] . "'>
								<input type='hidden' name='price' value='" . $row2['price'] . "'>
								<input type='submit' class='customer_button' name='edit_offer_button' value='Διόρθωση'>
								</form></td>
								<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $row2['customer_id'] . "'>
								<input type='submit' class='customer_button' name='customer_tab' value='Προβολή'>
								</form></td>
								</tr>");
						}
						echo("</table><br/>");
					} else echo("<br/>Δεν βρέθηκαν εγγραφές Προσφορών.<br/>");
				}
				
				// Αναζήτηση ημερομηνιών στον πίνακα Purchases (ταξινόμηση με βάση την ημερομηνία)
				/*if (isset($_POST['purchases'])) {
					$sql3 = "SELECT * FROM Purchases WHERE invoice_date BETWEEN '" . $search_date1 . "' AND '" . $search_date2 . "' ORDER BY invoice_date ASC";
					$result3 = $conn -> query($sql3);
					if ($result3 -> num_rows > 0) {
						echo("<table id='search_purchases_table'>
							<caption><h3> Αγορές </h3></caption>
							<tr>
							<th>Ημερομηνία</th>
							<th>Επωνυμία</th>
							<th>Περιγραφή</th>
							<th>Τιμή</th>
							<th>Αρχείο</th>
							</tr>");
						while ($row3 = $result3 -> fetch_assoc()) {
							$res3 = $conn -> query("SELECT * FROM Suppliers WHERE supplier_id = '" . $row3['supplier_id'] . "'");
							$c = $res3 -> fetch_assoc();
							echo("<tr><td>" . $row3['offer_date'] . "</td><td>" . $c['surname'] . "</td><td>" . $c['name'] . "</td>
								<td>" . $row3['description'] . "</td><td>" . $row3['price'] . "</td><td>" . $row3['offer_link'] . "</td>");
							echo("<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $row3['customer_id'] . "'>
								<input type='hidden' name='offer_date' value='" . $row3['offer_date'] . "'>
								<input type='hidden' name='description' value='" . $row3['description'] . "'>
								<input type='hidden' name='price' value='" . $row3['price'] . "'>
								<input type='submit' class='customer_button' name='edit_offer_button' value='Διόρθωση'>
								</form></td>
								<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $row3['customer_id'] . "'>
								<input type='submit' class='customer_button' name='/*********supplier_tab*********' value='Προβολή'>
								</form></td>
								</tr>");
						}
						echo("</table><br/>");
					} else echo("<br/>Δεν βρέθηκαν εγγραφές Αγορών.<br/>");
				}*/
			} //Κουμπί "Υποβολή" αναζήτησης κινήσεων
			
			// Πάτημα κουμπιού "Συνολική Οφειλή"
			if (isset($_POST['total_debt_button'])) {
				$sql = "SELECT SUM(debt) AS debt FROM Customers";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					$row = $result -> fetch_assoc();
					$total_debt = $row['debt'];
					if ($total_debt == NULL) $total_debt = 0;
				}
				echo("<h3>Συνολική Οφειλή προς Εταιρεία: </h3>" . $total_debt . " €<br/>");
			} //Κουμπί "Συνολική Οφειλή"
			
			// Πάτημα κουμπιού "Backup"
			if (isset($_POST['backup_button'])) {
				$file = $dbname . "-" . date("Y-m-d") . ".sql";
				$dir1 = $database_backup1_dir . $file;
				$dir2 = $database_backup2_dir . $file;
				shell_exec("C:\\xampp\mysql\bin\mysqldump -u " . $username . " " . $dbname . " > " . $dir1);
				shell_exec("C:\\xampp\mysql\bin\mysqldump -u " . $username . " " . $dbname . " > " . $dir2);
				echo("<br/>Το backup αρχείο αποθηκεύτηκε επιτυχώς.<br/>");
			} //Κουμπί "Backup"
			
			// Πάτημα κουμπιού "Συγχρονισμός"
			if (isset($_POST['sync_button'])) {
				$check = 0;
				$sql = "SELECT customer_id FROM Customers";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					while ($row = $result -> fetch_assoc()) {
						$sql1 = "SELECT SUM(price - paid) AS debt FROM Customer_orders WHERE customer_id = '" . $row['customer_id'] . "'";
						$result1 = $conn -> query($sql1);
						$row1 = $result1 -> fetch_assoc();
						$sql2 = "UPDATE Customers SET debt = '" . $row1['debt'] . "' WHERE Customers.customer_id = '" . $row['customer_id'] . "'";
						$result2 = $conn -> query($sql2);
						if (!$result2) {
							echo("ERROR: " . $conn -> error);
							$check ++;
						}
					}
					if ($check == 0) echo("<br/>Όλες οι οφειλές ενημερώθηκαν επιτυχώς.<br/>");
					else echo("<br/>Υπήρξε σφάλμα.<br/>");
				} else echo("<br/>Δεν βρέθηκαν εγγραφές Πελατών.<br/>");
			} //Κουμπί "Συγχρονισμός"
			
			// Πάτημα κουμπιού "Υποβολή" αναζήτησης πελατών(με βάση επώνυμο, όνομα, εταιρεία ή σχόλια) [χωρίς checkbox]
			if (isset($_POST['search_button']) && !isset($_POST['search_all'])) {
				$search_term = validate_data($conn,$_POST['search_input']);
				
				echo("<h3> Αναζήτηση πελατών: </h3>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='search' name='search_input'>
					<input type='submit' class='customer_button' name='search_button' value='Υποβολή'>
					<input type='checkbox' name='search_all'> Αναζήτηση παντού<br/>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='submit' class='customer_button' name='new_customer_button' value='Νέος Πελάτης'>
					</form>");
				
				// Αναζήτηση με βάση επώνυμο, όνομα, εταιρεία ή σχόλια στον πίνακα Customers (ταξινόμηση με βάση το επώνυμο)
				$sql = "SELECT * FROM Customers WHERE LOWER(surname) LIKE LOWER('%" . $search_term . "%') OR LOWER(name) LIKE LOWER('%" . $search_term . "%') 
					OR LOWER(company_name) LIKE LOWER('%" . $search_term . "%') OR LOWER(comments) LIKE LOWER('%" . $search_term . "%') ORDER BY surname ASC";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					echo("<table id='search_customers_table'>
						<caption><h3> Πελάτες </h3></caption>
						<tr>
						<th></th>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th>Εταιρεία</th>
						<th>Email</th>
						<th>Τηλέφωνο 1</th>
						<th>Τηλέφωνο 2</th>
						<th>Σχόλια</th>
						<th>Οφειλή</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='submit' class='customer_button' name='customer_tab' value='Καρτέλα'>
							</form></td>
							<td>" . $row['surname'] . "</td><td>" . $row['name'] . "</td><td>" . $row['company_name'] . "</td><td>" . $row['email'] . "</td>
							<td>" . $row['telephone1'] . "</td><td>" . $row['telephone2'] . "</td><td>" . $row['comments'] . "</td><td>" . $row['debt'] . "</td>
							</tr>");
					}
					echo("</table><br/>");
				} else echo("<br/>Δεν βρέθηκαν εγγραφές Πελατών.<br/>");
			} //Κουμπί "Υποβολή" αναζήτησης πελατών
			
			// Πάτημα κουμπιού "Υποβολή" αναζήτησης πελατών παντού (εκτός από id πελάτη και links) [με checkbox]
			if (isset($_POST['search_button']) && isset($_POST['search_all'])) {
				$search_term = validate_data($conn,$_POST['search_input']);
				
				echo("<h3> Αναζήτηση πελατών: </h3>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='search' name='search_input'>
					<input type='submit' class='customer_button' name='search_button' value='Υποβολή'>
					<input type='checkbox' name='search_all'> Αναζήτηση παντού<br/>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='submit' class='customer_button' name='new_customer_button' value='Νέος Πελάτης'>
					</form>");
				
				// Αναζήτηση παντού στον πίνακα Customers (ταξινόμηση με βάση το επώνυμο)
				$sql = "SELECT * FROM Customers 
					WHERE LOWER(surname) LIKE LOWER('%" . $search_term . "%') OR LOWER(name) LIKE LOWER('%" . $search_term . "%') 
					OR LOWER(company_name) LIKE LOWER('%" . $search_term . "%') OR LOWER(email) LIKE LOWER('%" . $search_term . "%') 
					OR telephone1 LIKE '%" . $search_term . "%' OR telephone2 LIKE '%" . $search_term . "%' 
					OR LOWER(comments) LIKE LOWER('%" . $search_term . "%') OR debt LIKE '%" . $search_term . "%' ORDER BY surname ASC";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					echo("<table id='search_customers_table'>
						<caption><h3> Πελάτες </h3></caption>
						<tr>
						<th></th>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th>Εταιρεία</th>
						<th>Email</th>
						<th>Τηλέφωνο 1</th>
						<th>Τηλέφωνο 2</th>
						<th>Σχόλια</th>
						<th>Οφειλή</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='submit' class='customer_button' name='customer_tab' value='Καρτέλα'>
							</form></td>
							<td>" . $row['surname'] . "</td><td>" . $row['name'] . "</td><td>" . $row['company_name'] . "</td><td>" . $row['email'] . "</td>
							<td>" . $row['telephone1'] . "</td><td>" . $row['telephone2'] . "</td><td>" . $row['comments'] . "</td><td>" . $row['debt'] . "</td>
							</tr>");
					}
					echo("</table><br/>");
				} else echo("<br/>Δεν βρέθηκαν εγγραφές Πελατών.<br/>");
				
				// Αναζήτηση παντού στον πίνακα Customer_orders (ταξινόμηση με βάση την ημερομηνία)
				$sql1 = "SELECT * FROM Customer_orders 
					WHERE order_date LIKE '%" . $search_term . "%' OR LOWER(description) LIKE LOWER('%" . $search_term . "%') 
					OR price LIKE '%" . $search_term . "%' OR paid LIKE '%" . $search_term . "%' ORDER BY order_date DESC";
				$result1 = $conn -> query($sql1);
				if ($result1 -> num_rows > 0) {
					echo("<table id='search_orders_table'>
						<caption><h3> Παραγγελίες </h3></caption>
						<tr>
						<th>Ημερομηνία</th>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Πληρωμένο Ποσό</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row1 = $result1 -> fetch_assoc()) {
						$res1 = $conn -> query("SELECT * FROM Customers WHERE customer_id = '" . $row1['customer_id'] . "'");
						$a = $res1 -> fetch_assoc();
						echo("<tr><td>" . $row1['order_date'] . "</td><td>" . $a['surname'] . "</td><td>" . $a['name'] . "</td>
							<td>" . $row1['description'] . "</td><td>" . $row1['price'] . "</td>
							<td>" . $row1['paid'] . "</td><td>" . $row1['order_link'] . "</td>");
						echo("<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row1['customer_id'] . "'>
							<input type='hidden' name='order_date' value='" . $row1['order_date'] . "'>
							<input type='hidden' name='description' value='" . $row1['description'] . "'>
							<input type='hidden' name='price' value='" . $row1['price'] . "'>
							<input type='hidden' name='paid' value='" . $row1['paid'] . "'>
							<input type='submit' class='customer_button' name='edit_order_button' value='Διόρθωση'>
							</form></td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row1['customer_id'] . "'>
							<input type='submit' class='customer_button' name='customer_tab' value='Προβολή'>
							</form></td>
							</tr>");
					}
					echo("</table><br/>");
				} else echo("<br/>Δεν βρέθηκαν εγγραφές Παραγγελιών.<br/>");
				
				// Αναζήτηση παντού στον πίνακα Customer_offers (ταξινόμηση με βάση την ημερομηνία)
				$sql2 = "SELECT * FROM Customer_offers 
					WHERE offer_date LIKE '%" . $search_term . "%' OR LOWER(description) LIKE LOWER('%" . $search_term . "%') OR price LIKE '%" . $search_term . "%' ORDER BY offer_date DESC";
				$result2 = $conn -> query($sql2);
				if ($result2 -> num_rows > 0) {
					echo("<table id='search_offers_table'>
						<caption><h3> Προσφορές </h3></caption>
						<tr>
						<th>Ημερομηνία</th>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row2 = $result2 -> fetch_assoc()) {
						$res2 = $conn -> query("SELECT * FROM Customers WHERE customer_id = '" . $row2['customer_id'] . "'");
						$b = $res2 -> fetch_assoc();
						echo("<tr><td>" . $row2['offer_date'] . "</td><td>" . $b['surname'] . "</td><td>" . $b['name'] . "</td>
							<td>" . $row2['description'] . "</td><td>" . $row2['price'] . "</td><td>" . $row2['offer_link'] . "</td>");
						echo("<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row2['customer_id'] . "'>
							<input type='hidden' name='offer_date' value='" . $row2['offer_date'] . "'>
							<input type='hidden' name='description' value='" . $row2['description'] . "'>
							<input type='hidden' name='price' value='" . $row2['price'] . "'>
							<input type='submit' class='customer_button' name='edit_offer_button' value='Διόρθωση'>
							</form></td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row2['customer_id'] . "'>
							<input type='submit' class='customer_button' name='customer_tab' value='Προβολή'>
							</form></td>
							</tr>");
					}
					echo("</table><br/>");
				} else echo("<br/>Δεν βρέθηκαν εγγραφές Προσφορών.<br/>");
			} //Κουμπί "Υποβολή" αναζήτησης πελατών παντού
			
			
			/*///// ΠΕΛΑΤΕΣ /////*/
			// Πάτημα κουμπιού "Νέος Πελάτης"
			if (isset($_POST['new_customer_button'])) {
				echo("<h3>Προσθήκη νέου πελάτη</h3>
					<p><span class='error'>* υποχρεωτικό πεδίο</span></p>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return validate_customer()'>
					Επώνυμο: <input type='text' name='surname' id='epwnymo'>
					<span class='error'>*</span><br/>
					Όνομα: <input type='text' name='name' id='onoma'>
					<span class='error'>*</span><br/>
					Εταιρεία: <input type='text' name='company_name' id='etaireia'><br/>
					Email: <input type='email' name='email' id='email'><br/>
					Τηλέφωνο1: <input type='tel' name='telephone1' id='thl1' maxlength='10' pattern='[0-9]{10}'><br/>
					Τηλέφωνο2: <input type='tel' name='telephone2' id='thl2' maxlength='10' pattern='[0-9]{10}'><br/><br/>
					Σχόλια: <textarea name='comments' id='sxolia' rows='5' cols='40'></textarea><br/><br/>
					<input type='submit' class='customer_button' name='confirm_customer_button' value='Καταχώρηση'>
					</form>");
				echo("<p class='error' id='error_para'></p>");
			} //Κουμπί "Νέος Πελάτης"
			
			// Πάτημα κουμπιού "Καταχώρηση" πελάτη
			if (isset($_POST['confirm_customer_button'])) {
				$surname = validate_data($conn,$_POST['surname']);
				$name = validate_data($conn,$_POST['name']);
				$company = validate_data($conn,$_POST['company_name']);
				$email = validate_data($conn,$_POST['email']);
				$telephone1 = validate_data($conn,$_POST['telephone1']);
				$telephone2 = validate_data($conn,$_POST['telephone2']);
				$comments = validate_data($conn,$_POST['comments']);
				
				// Προσθήκη εγγραφής στον πίνακα Customers
				$sql = "INSERT INTO Customers(customer_id,surname,name,company_name,email,telephone1,telephone2,comments,debt) VALUES 
				(NULL,'" . $surname . "','" . $name . "','" . $company . "','" . $email . "','" . $telephone1 . "','" . $telephone2 . "','" . $comments . "','0')";
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else {
					$last_id = $conn -> insert_id;
					// Δημιουργία φακέλου πελάτη
					$dir = $customer_dir . $surname . " " . $name;
					if (!file_exists($dir)) {
						mkdir($dir, 0777, true);
						mkdir($dir . "\Παραγγελίες", 0777, true);
						mkdir($dir . "\Προσφορές", 0777, true);
						echo("<br/>Ο φάκελος δημιουργήθηκε!");
					}
					else {
						mkdir($dir . "(new)", 0777, true);
						mkdir($dir . "(new)\Παραγγελίες", 0777, true);
						mkdir($dir . "(new)\Προσφορές", 0777, true);
						echo("<br/>ΠΡΟΣΟΧΗ!!! Ο φάκελος υπάρχει ήδη! Δημιουργήθηκε φάκελος με όνομα " . $surname . " " . $name . "(new).");
					}
					// Δημιουργία backup φακέλου πελάτη
					$dir0 = $customer_backup_dir . $surname . " " . $name;
					if (!file_exists($dir0)) {
						mkdir($dir0, 0777, true);
						mkdir($dir0 . "\Παραγγελίες", 0777, true);
						mkdir($dir0 . "\Προσφορές", 0777, true);
					}
					else {
						mkdir($dir0 . "(new)", 0777, true);
						mkdir($dir0 . "(new)\Παραγγελίες", 0777, true);
						mkdir($dir0 . "(new)\Προσφορές", 0777, true);
					}
					echo("<br/>Κατεχωρήθη.<br/>");
				}
					
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $last_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Καρτέλα'>
					</form>");
				// Κουμπί για καταχώρηση επόμενου πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='submit' class='customer_button' name='new_customer_button' value='Νέος Πελάτης'>
					</form>");
			} //Κουμπί "Καταχώρηση" πελάτη
			
			// Πάτημα κουμπιού "Καρτέλα" (ή "Επιστροφή" στην Καρτέλα)
			if (isset($_POST['customer_tab'])) {
				// Εμφάνιση των στοιχείων του συγκεκριμένου πελάτη
				$customer_id = $_POST['customer_field'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				echo("<i>Εταιρεία: " . $row['company_name'] . "&emsp;&emsp;Email: " . $row['email'] . "&emsp;&emsp;Τηλέφωνα: " . $row['telephone1']);
				if ($row['telephone1'] != NULL && $row['telephone2'] != NULL) echo(", ");
				echo($row['telephone2'] . "&emsp;&emsp;Σχόλια: " . $row['comments'] . "</i><br/>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_folder' value='Φάκελος'>
					<input type='submit' class='customer_button' name='edit_customer_button' value='Διόρθωση'>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return delete_customer_confirmation()'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='delete_customer_button' value='Διαγραφή'>
					</form>");
				
				// Οφειλή του συγκεκριμένου πελάτη
				$sql = "SELECT debt FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<br/><u>Συνολική Οφειλή πελάτη:</u> " . $row['debt'] . " €");
				
				// Εμφάνιση όλων των παραγγελιών του συγκεκριμένου πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_orders WHERE customer_id = '" . $customer_id . "' ORDER BY order_date DESC";
				$result = $conn -> query($sql);
				echo("<br/><h3>&emsp;&emsp;&emsp;&emsp;Παραγγελίες</h3>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						<input type='submit' class='customer_button' name='alter_orders_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_orders_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Πληρωμένο Ποσό</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['order_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['paid'] . "</td>
							<td>");
						if ($row['order_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_link' value='" . $row['order_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['order_link'] . "' name='open_order' value='Άνοιγμα'>
							</form>");
						}	
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Παραγγελιών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						</form>");
				}
				
				// Εμφάνιση όλων των προσφορών για τον συγκεκριμένο πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_offers WHERE customer_id = '" . $customer_id . "' ORDER BY offer_date DESC";
				$result = $conn -> query($sql);
				echo("<h3>&emsp;&emsp;&emsp;&emsp;Προσφορές&emsp;<button onclick='hide_show()'>></button></h3>");
				
				echo("<div id='prosfores'>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						<input type='submit' class='customer_button' name='alter_offers_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_offers_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['offer_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td>
							<td>");
						if ($row['offer_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_link' value='" . $row['offer_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['offer_link'] . "' name='open_offer' value='Άνοιγμα'>
							</form>");
						}
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Προσφορών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						</form>");
				}
				echo("</div>");
			} //Κουμπί "Καρτέλα" (ή "Επιστροφή" στην Καρτέλα)
			
			// Πάτημα κουμπιού "Φάκελος" (ουσιαστικά "Καρτέλα")
			if (isset($_POST['customer_folder'])) {
				// Εμφάνιση των στοιχείων του συγκεκριμένου πελάτη
				$customer_id = $_POST['customer_field'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				$dir = $customer_dir . $row['surname'] . " " . $row['name'];
				exec("explorer /e, " . $dir);
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				echo("<i>Εταιρεία: " . $row['company_name'] . "&emsp;&emsp;Email: " . $row['email'] . "&emsp;&emsp;Τηλέφωνα: " . $row['telephone1']);
				if ($row['telephone1'] != NULL && $row['telephone2'] != NULL) echo(", ");
				echo($row['telephone2'] . "&emsp;&emsp;Σχόλια: " . $row['comments'] . "</i><br/>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_folder' value='Φάκελος'>
					<input type='submit' class='customer_button' name='edit_customer_button' value='Διόρθωση'>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return delete_customer_confirmation()'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='delete_customer_button' value='Διαγραφή'>
					</form>");
				
				// Οφειλή του συγκεκριμένου πελάτη
				$sql = "SELECT debt FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<br/><u>Συνολική Οφειλή πελάτη:</u> " . $row['debt'] . " €");
				
				// Εμφάνιση όλων των παραγγελιών του συγκεκριμένου πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_orders WHERE customer_id = '" . $customer_id . "' ORDER BY order_date DESC";
				$result = $conn -> query($sql);
				echo("<br/><h3>&emsp;&emsp;&emsp;&emsp;Παραγγελίες</h3>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						<input type='submit' class='customer_button' name='alter_orders_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_orders_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Πληρωμένο Ποσό</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['order_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['paid'] . "</td>
							<td>");
						if ($row['order_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_link' value='" . $row['order_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['order_link'] . "' name='open_order' value='Άνοιγμα'>
							</form>");
						}	
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Παραγγελιών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						</form>");
				}
				
				// Εμφάνιση όλων των προσφορών για τον συγκεκριμένο πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_offers WHERE customer_id = '" . $customer_id . "' ORDER BY offer_date DESC";
				$result = $conn -> query($sql);
				echo("<h3>&emsp;&emsp;&emsp;&emsp;Προσφορές&emsp;<button onclick='hide_show()'>></button></h3>");
				
				echo("<div id='prosfores'>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						<input type='submit' class='customer_button' name='alter_offers_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_offers_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['offer_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td>
							<td>");
						if ($row['offer_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_link' value='" . $row['offer_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['offer_link'] . "' name='open_offer' value='Άνοιγμα'>
							</form>");
						}
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Προσφορών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						</form>");
				}
				echo("</div>");
			} //Κουμπί "Φάκελος"
			
			// Πάτημα κουμπιού "Διόρθωση" πελάτη
			if (isset($_POST['edit_customer_button'])) {
				$customer_id = $_POST['customer_field'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h3>Διόρθωση στοιχείων πελάτη</h3>Id: " . $customer_id);
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='hidden' name='surname' value='" . $row['surname'] . "'>
					<input type='hidden' name='name' value='" . $row['name'] . "'>
					Επώνυμο: <input type='text' name='new_surname' placeholder='" . $row['surname'] . "' value='" . $row['surname'] . "'><br/>
					Όνομα: <input type='text' name='new_name' placeholder='" . $row['name'] . "' value='" . $row['name'] . "'><br/>
					Εταιρεία: <input type='text' name='company_name' value='" . $row['company_name'] . "'><br/>
					Email: <input type='email' name='email' value='" . $row['email'] . "'><br/>
					Τηλέφωνο1: <input type='tel' name='telephone1' maxlength='10' pattern='[0-9]{10}' value='" . $row['telephone1'] . "'><br/>
					Τηλέφωνο2: <input type='tel' name='telephone2' maxlength='10' pattern='[0-9]{10}' value='" . $row['telephone2'] . "'><br/><br/>
					Σχόλια: <textarea name='comments' rows='5' cols='40'>" . $row['comments'] . "</textarea><br/><br/>
					<input type='submit' class='customer_button' name='confirm_edit_customer' value='Καταχώρηση'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "Διόρθωση" πελάτη
			
			// Πάτημα κουμπιού "Καταχώρηση" διόρθωσης πελάτη
			if (isset($_POST['confirm_edit_customer'])) {
				$customer_id = $_POST['customer_field'];
				$surname = $_POST['surname'];
				$name = $_POST['name'];
				$new_surname = validate_data($conn,$_POST['new_surname']);
				$new_name = validate_data($conn,$_POST['new_name']);
				$company = validate_data($conn,$_POST['company_name']);
				$email = validate_data($conn,$_POST['email']);
				$telephone1 = validate_data($conn,$_POST['telephone1']);
				$telephone2 = validate_data($conn,$_POST['telephone2']);
				$comments = validate_data($conn,$_POST['comments']);
				
				// Ελέγχει αν επώνυμο/όνομα έχουν αλλαχθεί (και δεν είναι κενά) και σχηματίζει το ανάλογο query
				$sql = "UPDATE Customers SET ";
				if ($new_surname != "" && $new_surname != $surname) {
					$sql .= "surname = '" . $new_surname . "', ";
				}
				if ($new_name != ""  && $new_name != $name) {
					$sql .= "name = '" . $new_name . "', ";
				}
				$sql .= "company_name = '" . $company . "', email = '" . $email . "', telephone1 = '" . $telephone1 . "', telephone2 = '" . $telephone2 . "', comments = '" . $comments . "'";
				$sql .= " WHERE Customers.customer_id = '" . $customer_id . "'";
				// Αλλαγή εγγραφής στον πίνακα Customers
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else {
					// Μετονομασία φακέλου πελάτη αν έχουν αλλαχθεί επώνυμο/όνομα
					if ($new_surname != $surname || $new_name != $name) {
						$dir = $customer_dir . $surname . " " . $name;
						$dir0 = $customer_backup_dir . $surname . " " . $name;
						$new_dir = $customer_dir . $new_surname . " " . $new_name;
						$new_dir0 = $customer_backup_dir . $new_surname . " " . $new_name;
						if (!file_exists($new_dir)) {
							rename($dir, $new_dir);
							rename($dir0, $new_dir0);
						} else {
							rename($dir, $new_dir . "(new)");
							rename($dir0, $new_dir0 . "(new)");
							echo("<br/>ΠΡΟΣΟΧΗ!!! Ο φάκελος υπάρχει ήδη! Δημιουργήθηκε φάκελος με όνομα " . $new_surname . " " . $new_name . "(new).");
						}
					}
					echo("<br/>Κατεχωρήθη.<br/>");
				}
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Καρτέλα'>
					</form>");
			} //Κουμπί "Καταχώρηση" διόρθωσης πελάτη
			
			// Πάτημα κουμπιού "Διαγραφή" πελάτη
			if (isset($_POST['delete_customer_button'])) {
				$customer_id = $_POST['customer_field'];
				// Αφαίρεση εγγραφών από όλους τους πίνακες
				$sql1 = "DELETE FROM Customer_orders WHERE Customer_orders.customer_id = '" . $customer_id . "'";
				$sql2 = "DELETE FROM Customer_offers WHERE Customer_offers.customer_id = '" . $customer_id . "'";
				$sql = "DELETE FROM Customers WHERE Customers.customer_id = '" . $customer_id . "'";
				$result1 = $conn -> query($sql1);
				$result2 = $conn -> query($sql2);
				$result = $conn -> query($sql);
				if (!$result1 || !$result2 || !$result) echo("ERROR: " . $conn -> error);
				else echo("<br/>Διεγράφη.<br/>");
				
				// Κουμπί επιστροφής στους "Πελάτες"
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='submit' class='customer_button' name='customers_button' value='Επιστροφή'>
					</form>");
			} //Κουμπί "Διαγραφή" πελάτη
			/*///// ΠΕΛΑΤΕΣ /////*/
			
			
			/*///// ΠΑΡΑΓΓΕΛΙΕΣ /////*/
			// Πάτημα κουμπιού "Νέα Παραγγελία"
			if (isset($_POST['new_order_button'])) {
				$customer_id = $_POST['customer_field'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
				
				// Οφειλή του συγκεκριμένου πελάτη
				echo("<br/><u>Συνολική Οφειλή πελάτη:</u> " . $row['debt'] . " €");
				
				// Εμφάνιση όλων των παραγγελιών του συγκεκριμένου πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_orders WHERE customer_id = '" . $customer_id . "' ORDER BY order_date DESC";
				$result = $conn -> query($sql);
				echo("<table id='customer_orders_table'>
					<caption><h3> Παραγγελίες </h3></caption>
					<tr>
					<th>Ημερομηνία</th>
					<th>Περιγραφή</th>
					<th>Τιμή</th>
					<th>Πληρωμένο Ποσό</th>
					<th>Αρχείο</th>
					</tr>");
				echo("<tr>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return validate_entries()'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "' id='id_pelath'>
					<td><input type='date' name='order_date' id='hmeromhnia'><span class='error'>*</span></td>
					<td><input type='text' name='description' id='perigrafh'><span class='error'>*</span></td>
					<td><input type='number' step='0.01' min='0' name='price' id='timh'><span class='error'>*</span></td>
					<td><input type='number' step='0.01' min='0' name='paid' id='plhrwmeno_poso'></td>
					<td><input type='file' name='order_link' id='syndesmos'></td>
					<td><input type='submit' class='customer_button' name='confirm_order_button' value='OK'></td>
					</form>
					</tr>");
				if ($result -> num_rows > 0) {
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['order_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td>
							<td>" . $row['paid'] . "</td><td>" . $row['order_link'] . "</td></tr>");
					}
				}
				echo("</table><br/>");
				echo("<p class='error' id='error_para'></p>");
			} //Κουμπί "Νέα Παραγγελία"
			
			// Πάτημα κουμπιού "Καταχώρηση" παραγγελίας
			if (isset($_POST['confirm_order_button'])) {
				$customer_id = $_POST['customer_field'];
				$order_date = validate_data($conn,$_POST['order_date']);
				$description = validate_data($conn,$_POST['description']);
				$price = validate_data($conn,$_POST['price']);
				$paid = validate_data($conn,$_POST['paid']);
				$order_link = validate_data($conn,$_POST['order_link']);
				
				// Μεταφορά αρχείου εικόνας στον φάκελο παραγγελιών του πελάτη
				if ($order_link != NULL) {
					$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
					$result = $conn -> query($sql);
					$row = $result -> fetch_assoc();
					$dir = $source_dir . $order_link;
					$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Παραγγελίες\\" . $order_date;
					if (!file_exists($new_dir . ".jpg")) {
						$order_link = $order_date;
						copy($dir, $new_dir . ".jpg");
						echo("Το αρχείο αποθηκεύτηκε επιτυχώς.");
					} else {
						$counter = 1;
						do {
							$order_link = $order_date . "(" . $counter . ")";
							$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Παραγγελίες\\" . $order_link;
							$counter ++;
						} while (file_exists($new_dir . ".jpg"));
						copy($dir, $new_dir . ".jpg");
						echo("<br/>Το αρχείο υπάρχει ήδη! Δημιουργήθηκε αρχείο με όνομα " . $order_link);
					}
					rename($dir, $dump_dir . $order_link . ".jpg");
				}
				
				// Προσθήκη εγγραφής στον πίνακα Customer_orders
				$sql = "INSERT INTO Customer_orders(customer_id,order_date,description,price,paid,order_link) 
					VALUES ('" . $customer_id . "','" . $order_date . "','" . $description . "','" . $price . "','" . $paid . "','" . $order_link . "')";
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else {
					echo("<br/>Κατεχωρήθη.<br/>");
					// Ενημέρωση οφειλής του πελάτη στον πίνακα Customers
					if ($paid == NULL) $paid = 0;
					$debt = $price - $paid;
					$sql = "UPDATE Customers SET debt = debt + " . $debt . " WHERE Customers.customer_id = '" . $customer_id . "'";
					$result = $conn -> query($sql);
					if (!$result) echo("ERROR: " . $conn -> error);
					else echo("Η οφειλή ενημερώθηκε.<br/>");
					/*// Μεταφορά αρχείου εικόνας στον φάκελο παραγγελιών του πελάτη
					if ($order_link != NULL) {
						$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
						$result = $conn -> query($sql);
						$row = $result -> fetch_assoc();
						$dir = $source_dir . $order_link;
						$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Παραγγελίες\\" . $order_date;
						if (!file_exists($new_dir . ".jpg")) {
							$order_link = $order_date;
							copy($dir, $new_dir . ".jpg");
							echo("Το αρχείο αποθηκεύτηκε επιτυχώς.");
						} else {
							$counter = 1;
							do {
								$order_link = $order_date . "(" . $counter . ")";
								$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Παραγγελίες\\" . $order_link;
								$counter ++;
							} while (file_exists($new_dir . ".jpg"));
							copy($dir, $new_dir . ".jpg");
							echo("<br/>Το αρχείο υπάρχει ήδη! Δημιουργήθηκε αρχείο με όνομα " . $order_link);
						}
						rename($dir, $dump_dir . $order_link . ".jpg");
					}*/
				}
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "Καταχώρηση" παραγγελίας
			
			// Πάτημα κουμπιού "Διόρθωση/Διαγραφή" παραγγελίας
			if (isset($_POST['alter_orders_button'])) {
				$customer_id = $_POST['customer_field'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
				
				// Οφειλή του συγκεκριμένου πελάτη
				echo("<br/><u>Συνολική Οφειλή πελάτη:</u> " . $row['debt'] . " €<br/>");
				
				// Εμφάνιση όλων των παραγγελιών του συγκεκριμένου πελάτη μαζί με κουμπιά για μεταβολές (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_orders WHERE customer_id = '" . $customer_id . "' ORDER BY order_date DESC";
				$result = $conn -> query($sql);
				echo("<br/><h3>&emsp;&emsp;&emsp;&emsp;Παραγγελίες</h3>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return delete_all_confirmation()'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='delete_all_button' value='Διαγραφή Όλων'>
						</form>
						<table id='customer_orders_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Πληρωμένο Ποσό</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr>
							<td>" . $row['order_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['paid'] . "</td><td>" . $row['order_link'] . "</td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_date' value='" . $row['order_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='hidden' name='price' value='" . $row['price'] . "'>
							<input type='hidden' name='paid' value='" . $row['paid'] . "'>
							<input type='submit' class='customer_button' name='edit_order_button' value='Διόρθωση'>
							</form></td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return confirmation()'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_date' value='" . $row['order_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='hidden' name='price' value='" . $row['price'] . "'>
							<input type='hidden' name='paid' value='" . $row['paid'] . "'>
							<input type='submit' class='customer_button' name='delete_order_button' value='Διαγραφή'>
							</form></td>
							</tr>");
					}
					echo("</table><br/>");
				}
			} //Κουμπί "Διόρθωση/Διαγραφή" παραγγελίας
			
			// Πάτημα κουμπιού "Διαγραφή Όλων" των παραγγελιών
			if (isset($_POST['delete_all_button'])) {
				$customer_id = $_POST['customer_field'];
				// Αφαίρεση όλων των εγγραφών του συγκεκριμένου πελάτη από τον πίνακα Customer_orders
				$sql = "DELETE FROM Customer_orders WHERE Customer_orders.customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else {
					echo("<br/>Διεγράφησαν.<br/>");
					// Ενημέρωση οφειλής του πελάτη στον πίνακα Customers
					$sql = "UPDATE Customers SET debt = '0' WHERE Customers.customer_id = '" . $customer_id . "'";
					$result = $conn -> query($sql);
					if (!$result) echo("ERROR: " . $conn -> error);
					else echo("Η οφειλή ενημερώθηκε.<br/>");
				}
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "Διαγραφή Όλων" των παραγγελιών
			
			// Πάτημα κουμπιού "Διόρθωση" παραγγελίας
			if (isset($_POST['edit_order_button'])) {
				$customer_id = $_POST['customer_field'];
				$order_date = $_POST['order_date'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$paid = $_POST['paid'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
				
				// Οφειλή του συγκεκριμένου πελάτη
				echo("<br/><u>Συνολική Οφειλή πελάτη:</u> " . $row['debt'] . " €");
				
				// Εμφάνιση όλων των παραγγελιών του συγκεκριμένου πελάτη μαζί με κουμπιά για μεταβολές (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_orders WHERE customer_id = '" . $customer_id . "' ORDER BY order_date DESC";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					echo("<table id='customer_orders_table'>
						<caption><h3> Παραγγελίες </h3></caption>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Πληρωμένο Ποσό</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						// Διόρθωση της ζητούμενης παραγγελίας
						if ($row['order_date'] == $order_date && $row['description'] == $description) {
							echo("<tr>
								<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $customer_id . "'>
								<input type='hidden' name='order_date' value='" . $order_date . "'>
								<input type='hidden' name='description' value='" . $description . "'>
								<input type='hidden' name='price' value='" . $price . "'>
								<input type='hidden' name='paid' value='" . $paid . "'>
								<td><input type='date' name='new_order_date' placeholder='" . $row['order_date'] . "' value='" . $row['order_date'] . "'></td>
								<td><input type='text' name='new_description' placeholder='" . $row['description'] . "' value='" . $row['description'] . "'></td>
								<td><input type='number' step='0.01' min='0' name='new_price' placeholder='" . $row['price'] . "' value='" . $row['price'] . "'></td>
								<td><input type='number' step='0.01' min='0' name='new_paid' value='" . $row['paid'] . "'></td>
								<td><input type='text' name='order_link' value='" . $row['order_link'] . "'></td>
								<td><input type='submit' class='customer_button' name='confirm_edit_order' value='OK'></td>
								</form>
								</tr>");
							continue;
						}
						echo("<tr>
							<td>" . $row['order_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['paid'] . "</td><td>" . $row['order_link'] . "</td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_date' value='" . $row['order_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='hidden' name='price' value='" . $row['price'] . "'>
							<input type='hidden' name='paid' value='" . $row['paid'] . "'>
							<input type='submit' class='customer_button' name='edit_order_button' value='Διόρθωση'>
							</form></td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return confirmation()'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_date' value='" . $row['order_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='hidden' name='price' value='" . $row['price'] . "'>
							<input type='hidden' name='paid' value='" . $row['paid'] . "'>
							<input type='submit' class='customer_button' name='delete_order_button' value='Διαγραφή'>
							</form></td>
							</tr>");
					}
					echo("</table><br/>");
				}
			} //Κουμπί "Διόρθωση" παραγγελίας
			
			// Πάτημα κουμπιού "OK" διόρθωσης παραγγελίας
			if (isset($_POST['confirm_edit_order'])) {
				$customer_id = $_POST['customer_field'];
				$order_date = $_POST['order_date'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$paid = $_POST['paid'];
				$new_order_date = validate_data($conn,$_POST['new_order_date']);
				$new_description = validate_data($conn,$_POST['new_description']);
				$new_price = validate_data($conn,$_POST['new_price']);
				$new_paid = validate_data($conn,$_POST['new_paid']);
				$order_link = validate_data($conn,$_POST['order_link']);
				
				// Ελέγχει αν ημερομηνία/περιγραφή/τιμή έχουν αλλαχθεί (και δεν είναι κενά) και σχηματίζει το ανάλογο query
				$sql = "UPDATE Customer_orders SET ";
				if ($new_order_date != "" && $new_order_date != $order_date) {
					$sql .= "order_date = '" . $new_order_date . "', ";
				}
				if ($new_description != "" && $new_description != $description) {
					$sql .= "description = '" . $new_description . "', ";
				}
				if ($new_price != "" && $new_price != $price) {
					$sql .= "price = '" . $new_price . "', ";
				}
				$sql .= "paid = '" . $new_paid . "', order_link = '" . $order_link . "'";
				$sql .= " WHERE Customer_orders.customer_id = '" . $customer_id . "' AND Customer_orders.order_date = '" . $order_date . "' AND Customer_orders.description = '" . $description . "'";
				// Αλλαγή εγγραφής στον πίνακα Customer_orders
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else {
					echo("<br/>Κατεχωρήθη.<br/>");
					// Ενημέρωση οφειλής του πελάτη στον πίνακα Customers
					if ($paid == NULL) $paid = 0;
					if ($new_paid == NULL) $new_paid = 0;
					$previous_debt = $price - $paid;
					$new_debt = $new_price - $new_paid;
					$diff = $new_debt - $previous_debt;
					$sql = "UPDATE Customers SET debt = debt + " . $diff . " WHERE Customers.customer_id = '" . $customer_id . "'";
					$result = $conn -> query($sql);
					if (!$result) echo("ERROR: " . $conn -> error);
					else echo("Η οφειλή ενημερώθηκε.<br/>");
				}
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "OK" διόρθωσης παραγγελίας
			
			// Πάτημα κουμπιού "Διαγραφή" παραγγελίας
			if (isset($_POST['delete_order_button'])) {
				$customer_id = $_POST['customer_field'];
				$order_date = $_POST['order_date'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$paid = $_POST['paid'];
				
				// Αφαίρεση εγγραφής από τον πίνακα Customer_orders
				$sql = "DELETE FROM Customer_orders WHERE Customer_orders.customer_id = '" . $customer_id . "' AND Customer_orders.order_date = '" . $order_date . "' AND Customer_orders.description = '" . $description . "'";
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else {
					echo("<br/>Διεγράφη.<br/>");
					// Ενημέρωση οφειλής του πελάτη στον πίνακα Customers
					if ($paid == NULL) $paid = 0;
					$previous_debt = $price - $paid;
					$sql = "UPDATE Customers SET debt = debt - " . $previous_debt . " WHERE Customers.customer_id = '" . $customer_id . "'";
					$result = $conn -> query($sql);
					if (!$result) echo("ERROR: " . $conn -> error);
					else echo("Η οφειλή ενημερώθηκε.<br/>");
				}
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "Διαγραφή" παραγγελίας
			
			// Πάτημα κουμπιού "Άνοιγμα" αρχείου παραγγελίας (ουσιαστικά "Καρτέλα")
			if (isset($_POST['open_order'])) {
				// Εμφάνιση των στοιχείων του συγκεκριμένου πελάτη
				$customer_id = $_POST['customer_field'];
				$order_link = $_POST['order_link'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				$dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Παραγγελίες\\" . $order_link . ".jpg";
				exec("explorer /e, " . $dir);
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				echo("<i>Εταιρεία: " . $row['company_name'] . "&emsp;&emsp;Email: " . $row['email'] . "&emsp;&emsp;Τηλέφωνα: " . $row['telephone1']);
				if ($row['telephone1'] != NULL && $row['telephone2'] != NULL) echo(", ");
				echo($row['telephone2'] . "&emsp;&emsp;Σχόλια: " . $row['comments'] . "</i><br/>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_folder' value='Φάκελος'>
					<input type='submit' class='customer_button' name='edit_customer_button' value='Διόρθωση'>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return delete_customer_confirmation()'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='delete_customer_button' value='Διαγραφή'>
					</form>");
				
				// Οφειλή του συγκεκριμένου πελάτη
				$sql = "SELECT debt FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<br/><u>Συνολική Οφειλή πελάτη:</u> " . $row['debt'] . " €");
				
				// Εμφάνιση όλων των παραγγελιών του συγκεκριμένου πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_orders WHERE customer_id = '" . $customer_id . "' ORDER BY order_date DESC";
				$result = $conn -> query($sql);
				echo("<br/><h3>&emsp;&emsp;&emsp;&emsp;Παραγγελίες</h3>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						<input type='submit' class='customer_button' name='alter_orders_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_orders_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Πληρωμένο Ποσό</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['order_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['paid'] . "</td>
							<td>");
						if ($row['order_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_link' value='" . $row['order_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['order_link'] . "' name='open_order' value='Άνοιγμα'>
							</form>");
						}	
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Παραγγελιών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						</form>");
				}
				
				// Εμφάνιση όλων των προσφορών για τον συγκεκριμένο πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_offers WHERE customer_id = '" . $customer_id . "' ORDER BY offer_date DESC";
				$result = $conn -> query($sql);
				echo("<h3>&emsp;&emsp;&emsp;&emsp;Προσφορές&emsp;<button onclick='hide_show()'>></button></h3>");
				
				echo("<div id='prosfores'>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						<input type='submit' class='customer_button' name='alter_offers_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_offers_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['offer_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td>
							<td>");
						if ($row['offer_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_link' value='" . $row['offer_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['offer_link'] . "' name='open_offer' value='Άνοιγμα'>
							</form>");
						}
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Προσφορών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						</form>");
				}
				echo("</div>");
			} //Κουμπί "Άνοιγμα" αρχείου παραγγελίας
			/*///// ΠΑΡΑΓΓΕΛΙΕΣ /////*/
			
			
			/*///// ΠΡΟΣΦΟΡΕΣ /////*/
			// Πάτημα κουμπιού "Νέα Προσφορά"
			if (isset($_POST['new_offer_button'])) {
				$customer_id = $_POST['customer_field'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
				
				// Εμφάνιση όλων των προσφορών για τον συγκεκριμένο πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_offers WHERE customer_id = '" . $customer_id . "' ORDER BY offer_date DESC";
				$result = $conn -> query($sql);
				echo("<h3>&emsp;&emsp;&emsp;&emsp;Προσφορές</h3>");
				echo("<table id='customer_offers_table'>
					<tr>
					<th>Ημερομηνία</th>
					<th>Περιγραφή</th>
					<th>Τιμή</th>
					<th>Αρχείο</th>
					</tr>");
				echo("<tr>
					<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return validate_entries()'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "' id='id_pelath'>
					<td><input type='date' name='offer_date' id='hmeromhnia'><span class='error'>*</span></td>
					<td><input type='text' name='description' id='perigrafh'><span class='error'>*</span></td>
					<td><input type='number' step='0.01' min='0' name='price' id='timh'><span class='error'>*</span></td>
					<td><input type='file' name='offer_link' id='syndesmos'></td>
					<td><input type='submit' class='customer_button' name='confirm_offer_button' value='OK'></td>
					</form>
					</tr>");
				if ($result -> num_rows > 0) {
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['offer_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['offer_link'] . "</td></tr>");
					}
				}
				echo("</table><br/>");
				echo("<p class='error' id='error_para'></p>");
			} //Κουμπί "Νέα Προσφορά"
			
			// Πάτημα κουμπιού "Καταχώρηση" προσφοράς
			if (isset($_POST['confirm_offer_button'])) {
				$customer_id = $_POST['customer_field'];
				$offer_date = validate_data($conn,$_POST['offer_date']);
				$description = validate_data($conn,$_POST['description']);
				$price = validate_data($conn,$_POST['price']);
				$offer_link = validate_data($conn,$_POST['offer_link']);
				
				// Μεταφορά αρχείου εικόνας στον φάκελο προσφορών του πελάτη
				if ($offer_link != NULL) {
					$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
					$result = $conn -> query($sql);
					$row = $result -> fetch_assoc();
					$dir = $source_dir . $offer_link;
					$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Προσφορές\\" . $offer_date;
					if (!file_exists($new_dir . ".jpg")) {
						$offer_link = $offer_date;
						copy($dir, $new_dir . ".jpg");
						echo("Το αρχείο αποθηκεύτηκε επιτυχώς.");
					} else {
						$counter = 1;
						do {
							$offer_link = $offer_date . "(" . $counter . ")";
							$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Προσφορές\\" . $offer_link;
							$counter ++;
						} while (file_exists($new_dir . ".jpg"));
						copy($dir, $new_dir . ".jpg");
						echo("<br/>Το αρχείο υπάρχει ήδη! Δημιουργήθηκε αρχείο με όνομα " . $offer_link);
					}
					rename($dir, $dump_dir . $offer_link . ".jpg");
				}
				
				// Προσθήκη εγγραφής στον πίνακα Customer_offers
				$sql = "INSERT INTO Customer_offers(customer_id,offer_date,description,price,offer_link) 
					VALUES ('" . $customer_id . "','" . $offer_date . "','" . $description . "','" . $price . "','" . $offer_link . "')";
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else {
					echo("<br/>Κατεχωρήθη.<br/>");
					/*// Μεταφορά αρχείου εικόνας στον φάκελο προσφορών του πελάτη
					if ($offer_link != NULL) {
						$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
						$result = $conn -> query($sql);
						$row = $result -> fetch_assoc();
						$dir = $source_dir . $offer_link;
						$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Προσφορές\\" . $offer_date;
						if (!file_exists($new_dir . ".jpg")) {
							$offer_link = $offer_date;
							copy($dir, $new_dir . ".jpg");
							echo("Το αρχείο αποθηκεύτηκε επιτυχώς.");
						} else {
							$counter = 1;
							do {
								$offer_link = $offer_date . "(" . $counter . ")";
								$new_dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Προσφορές\\" . $offer_link;
								$counter ++;
							} while (file_exists($new_dir . ".jpg"));
							copy($dir, $new_dir . ".jpg");
							echo("<br/>Το αρχείο υπάρχει ήδη! Δημιουργήθηκε αρχείο με όνομα " . $offer_link);
						}
						rename($dir, $dump_dir . $offer_link . ".jpg");
					}*/
				}
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "Καταχώρηση" προσφοράς
			
			// Πάτημα κουμπιού "Διόρθωση/Διαγραφή" προσφοράς
			if (isset($_POST['alter_offers_button'])) {
				$customer_id = $_POST['customer_field'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
				
				// Εμφάνιση όλων των προσφορών για τον συγκεκριμένο πελάτη μαζί με κουμπιά για μεταβολές (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_offers WHERE customer_id = '" . $customer_id . "' ORDER BY offer_date DESC";
				$result = $conn -> query($sql);
				echo("<br/><h3>&emsp;&emsp;&emsp;&emsp;Προσφορές</h3>");
				if ($result -> num_rows > 0) {
					echo("<table id='customer_offers_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr>
							<td>" . $row['offer_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['offer_link'] . "</td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_date' value='" . $row['offer_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='hidden' name='price' value='" . $row['price'] . "'>
							<input type='submit' class='customer_button' name='edit_offer_button' value='Διόρθωση'>
							</form></td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return confirmation()'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_date' value='" . $row['offer_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='submit' class='customer_button' name='delete_offer_button' value='Διαγραφή'>
							</form></td>
							</tr>");
					}
					echo("</table><br/>");
				}
			} //Κουμπί "Διόρθωση/Διαγραφή" προσφοράς
			
			// Πάτημα κουμπιού "Διόρθωση" προσφοράς
			if (isset($_POST['edit_offer_button'])) {
				$customer_id = $_POST['customer_field'];
				$offer_date = $_POST['offer_date'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
				
				// Εμφάνιση όλων των προσφορών για τον συγκεκριμένο πελάτη μαζί με κουμπιά για μεταβολές (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_offers WHERE customer_id = '" . $customer_id . "' ORDER BY offer_date DESC";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					echo("<table id='customer_offers_table'>
						<caption><h3> Προσφορές </h3></caption>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						// Διόρθωση της ζητούμενης προσφοράς
						if ($row['offer_date'] == $offer_date && $row['description'] == $description) {
							echo("<tr>
								<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
								<input type='hidden' name='customer_field' value='" . $customer_id . "'>
								<input type='hidden' name='offer_date' value='" . $offer_date . "'>
								<input type='hidden' name='description' value='" . $description . "'>
								<input type='hidden' name='price' value='" . $price . "'>
								<td><input type='date' name='new_offer_date' placeholder='" . $row['offer_date'] . "' value='" . $row['offer_date'] . "'></td>
								<td><input type='text' name='new_description' placeholder='" . $row['description'] . "' value='" . $row['description'] . "'></td>
								<td><input type='number' step='0.01' min='0' name='new_price' placeholder='" . $row['price'] . "' value='" . $row['price'] . "'></td>
								<td><input type='text' name='offer_link' value='" . $row['offer_link'] . "'></td>
								<td><input type='submit' class='customer_button' name='confirm_edit_offer' value='OK'></td>
								</form>
								</tr>");
							continue;
						}
						echo("<tr>
							<td>" . $row['offer_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['offer_link'] . "</td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_date' value='" . $row['offer_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='hidden' name='price' value='" . $row['price'] . "'>
							<input type='submit' class='customer_button' name='edit_offer_button' value='Διόρθωση'>
							</form></td>
							<td><form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return confirmation()'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_date' value='" . $row['offer_date'] . "'>
							<input type='hidden' name='description' value='" . $row['description'] . "'>
							<input type='submit' class='customer_button' name='delete_offer_button' value='Διαγραφή'>
							</form></td>
							</tr>");
					}
					echo("</table><br/>");
				}
			} //Κουμπί "Διόρθωση" προσφοράς
			
			// Πάτημα κουμπιού "OK" διόρθωσης προσφοράς
			if (isset($_POST['confirm_edit_offer'])) {
				$customer_id = $_POST['customer_field'];
				$offer_date = $_POST['offer_date'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$new_offer_date = validate_data($conn,$_POST['new_offer_date']);
				$new_description = validate_data($conn,$_POST['new_description']);
				$new_price = validate_data($conn,$_POST['new_price']);
				$offer_link = validate_data($conn,$_POST['offer_link']);
				
				// Ελέγχει αν ημερομηνία/περιγραφή/τιμή έχουν αλλαχθεί (και δεν είναι κενά) και σχηματίζει το ανάλογο query
				$sql = "UPDATE Customer_offers SET ";
				if ($new_offer_date != "" && $new_offer_date != $offer_date) {
					$sql .= "offer_date = '" . $new_offer_date . "', ";
				}
				if ($new_description != "" && $new_description != $description) {
					$sql .= "description = '" . $new_description . "', ";
				}
				if ($new_price != "" && $new_price != $price) {
					$sql .= "price = '" . $new_price . "', ";
				}
				$sql .= "offer_link = '" . $offer_link . "'";
				$sql .= " WHERE Customer_offers.customer_id = '" . $customer_id . "' AND Customer_offers.offer_date = '" . $offer_date . "' AND Customer_offers.description = '" . $description . "'";
				// Αλλαγή εγγραφής στον πίνακα Customer_offers
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else echo("<br/>Κατεχωρήθη.<br/>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "OK" διόρθωσης προσφοράς
			
			// Πάτημα κουμπιού "Διαγραφή" προσφοράς
			if (isset($_POST['delete_offer_button'])) {
				$customer_id = $_POST['customer_field'];
				$offer_date = $_POST['offer_date'];
				$description = $_POST['description'];
				
				// Αφαίρεση εγγραφής από τον πίνακα Customer_offers
				$sql = "DELETE FROM Customer_offers WHERE Customer_offers.customer_id = '" . $customer_id . "' AND Customer_offers.offer_date = '" . $offer_date . "' AND Customer_offers.description = '" . $description . "'";
				$result = $conn -> query($sql);
				if (!$result) echo("ERROR: " . $conn -> error);
				else echo("<br/>Διεγράφη.<br/>");
				
				// Κουμπί επιστροφής στην "Καρτέλα" του πελάτη
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_tab' value='Επιστροφή'>
					</form>");
			} //Κουμπί "Διαγραφή" προσφοράς
			
			// Πάτημα κουμπιού "Άνοιγμα" αρχείου προσφοράς (ουσιαστικά "Καρτέλα")
			if (isset($_POST['open_offer'])) {
				// Εμφάνιση των στοιχείων του συγκεκριμένου πελάτη
				$customer_id = $_POST['customer_field'];
				$offer_link = $_POST['offer_link'];
				$sql = "SELECT * FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				$dir = $customer_dir . $row['surname'] . " " . $row['name'] . "\Προσφορές\\" . $offer_link . ".jpg";
				exec("explorer /e, " . $dir);
				echo("<h2>" . $row['surname'] . " " . $row['name'] . "</h2>");
				echo("<i>Εταιρεία: " . $row['company_name'] . "&emsp;&emsp;Email: " . $row['email'] . "&emsp;&emsp;Τηλέφωνα: " . $row['telephone1']);
				if ($row['telephone1'] != NULL && $row['telephone2'] != NULL) echo(", ");
				echo($row['telephone2'] . "&emsp;&emsp;Σχόλια: " . $row['comments'] . "</i><br/>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='customer_folder' value='Φάκελος'>
					<input type='submit' class='customer_button' name='edit_customer_button' value='Διόρθωση'>
					</form>");
				echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post' onsubmit='return delete_customer_confirmation()'>
					<input type='hidden' name='customer_field' value='" . $customer_id . "'>
					<input type='submit' class='customer_button' name='delete_customer_button' value='Διαγραφή'>
					</form>");
				
				// Οφειλή του συγκεκριμένου πελάτη
				$sql = "SELECT debt FROM Customers WHERE customer_id = '" . $customer_id . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				echo("<br/><u>Συνολική Οφειλή πελάτη:</u> " . $row['debt'] . " €");
				
				// Εμφάνιση όλων των παραγγελιών του συγκεκριμένου πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_orders WHERE customer_id = '" . $customer_id . "' ORDER BY order_date DESC";
				$result = $conn -> query($sql);
				echo("<br/><h3>&emsp;&emsp;&emsp;&emsp;Παραγγελίες</h3>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						<input type='submit' class='customer_button' name='alter_orders_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_orders_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Πληρωμένο Ποσό</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['order_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td><td>" . $row['paid'] . "</td>
							<td>");
						if ($row['order_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='order_link' value='" . $row['order_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['order_link'] . "' name='open_order' value='Άνοιγμα'>
							</form>");
						}	
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Παραγγελιών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_order_button' value='Νέα Παραγγελία'>
						</form>");
				}
				
				// Εμφάνιση όλων των προσφορών για τον συγκεκριμένο πελάτη (ταξινόμηση με βάση την ημερομηνία)
				$sql = "SELECT * FROM Customer_offers WHERE customer_id = '" . $customer_id . "' ORDER BY offer_date DESC";
				$result = $conn -> query($sql);
				echo("<h3>&emsp;&emsp;&emsp;&emsp;Προσφορές&emsp;<button onclick='hide_show()'>></button></h3>");
				
				echo("<div id='prosfores'>");
				if ($result -> num_rows > 0) {
					echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						<input type='submit' class='customer_button' name='alter_offers_button' value='Διόρθωση/Διαγραφή'>
						</form>
						<table id='customer_offers_table'>
						<tr>
						<th>Ημερομηνία</th>
						<th>Περιγραφή</th>
						<th>Τιμή</th>
						<th>Αρχείο</th>
						</tr>");
					while ($row = $result -> fetch_assoc()) {
						echo("<tr><td>" . $row['offer_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['price'] . "</td>
							<td>");
						if ($row['offer_link'] != NULL) {
							echo("<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
							<input type='hidden' name='customer_field' value='" . $row['customer_id'] . "'>
							<input type='hidden' name='offer_link' value='" . $row['offer_link'] . "'>
							<input type='submit' class='customer_button' title='" . $row['offer_link'] . "' name='open_offer' value='Άνοιγμα'>
							</form>");
						}
						echo("</td></tr>");
					}
					echo("</table><br/>");
				} else {
					echo("Δεν βρέθηκαν εγγραφές Προσφορών.<br/>
						<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
						<input type='hidden' name='customer_field' value='" . $customer_id . "'>
						<input type='submit' class='customer_button' name='new_offer_button' value='Νέα Προσφορά'>
						</form>");
				}
				echo("</div>");
			} //Κουμπί "Άνοιγμα" αρχείου προσφοράς
			/*///// ΠΡΟΣΦΟΡΕΣ /////*/
			
			$conn -> close();
		?>
	</body>
</html>
