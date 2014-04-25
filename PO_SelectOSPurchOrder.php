<?php

/* $Id: PO_SelectOSPurchOrder.php 6592 2014-03-02 08:41:40Z daintree $*/

$PricesSecurity = 12;

include('includes/session.inc');

$Title = _('Search Outstanding Purchase Orders');

include('includes/header.inc');
include('includes/DefinePOClass.php');

if (isset($_GET['SelectedStockItem'])) {
	$SelectedStockItem = trim($_GET['SelectedStockItem']);
}
elseif (isset($_POST['SelectedStockItem'])) {
	$SelectedStockItem = trim($_POST['SelectedStockItem']);
}

if (isset($_GET['OrderNumber'])) {
	$OrderNumber = $_GET['OrderNumber'];
}
elseif (isset($_POST['OrderNumber'])) {
	$OrderNumber = $_POST['OrderNumber'];
}

if (isset($_GET['SelectedSupplier'])) {
	$SelectedSupplier = trim($_GET['SelectedSupplier']);
}
elseif (isset($_POST['SelectedSupplier'])) {
	$SelectedSupplier = trim($_POST['SelectedSupplier']);
}

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">
	<div>
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


if (isset($_POST['ResetPart'])) {
	unset($SelectedStockItem);
}

if (isset($OrderNumber) AND $OrderNumber != '') {
	if (!is_numeric($OrderNumber)) {
		echo '<br /><b>' . _('The Order Number entered') . ' <u>' . _('MUST') . '</u> ' . _('be numeric') . '.</b><br />';
		unset($OrderNumber);
	} else {
		echo _('Order Number') . ' - ' . $OrderNumber;
	}
} else {
	if (isset($SelectedSupplier)) {
		echo '<br />
				<div class="page_help_text">' . _('For supplier') . ': ' . $SelectedSupplier . ' ' . _('and') . ' ';
		echo '<input type="hidden" name="SelectedSupplier" value="' . $SelectedSupplier . '" />
				</div>';
	}
	if (isset($SelectedStockItem)) {
		echo '<input type="hidden" name="SelectedStockItem" value="' . $SelectedStockItem . '" />';
	}
}

if (isset($_POST['SearchParts'])) {
	if (isset($_POST['Keywords']) AND isset($_POST['StockCode'])) {
		echo '<div class="page_help_text">' . _('Stock description keywords have been used in preference to the Stock code extract entered') . '.</div>';
	}
	if ($_POST['Keywords']) {
		//insert wildcard characters in spaces
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					SUM(locstock.quantity) AS qoh,
					stockmaster.units,
					SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qord
				FROM stockmaster INNER JOIN locstock
					ON stockmaster.stockid = locstock.stockid
					INNER JOIN purchorderdetails
						ON stockmaster.stockid=purchorderdetails.itemcode
				WHERE purchorderdetails.completed=0
				AND stockmaster.description " . LIKE . " '" . $SearchString . "'
				AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
				ORDER BY stockmaster.stockid";


	} elseif ($_POST['StockCode']) {

		$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					SUM(locstock.quantity) AS qoh,
					SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qord,
					stockmaster.units
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				INNER JOIN purchorderdetails
				ON stockmaster.stockid=purchorderdetails.itemcode
				WHERE purchorderdetails.completed=0
				AND stockmaster.stockid " . LIKE . " '%" . $_POST['StockCode'] . "%'
				AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
				ORDER BY stockmaster.stockid";

	} elseif (!$_POST['StockCode'] AND !$_POST['Keywords']) {
		$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					SUM(locstock.quantity) AS qoh,
					stockmaster.units,
					SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qord
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				INNER JOIN purchorderdetails
				ON stockmaster.stockid=purchorderdetails.itemcode
				WHERE purchorderdetails.completed=0
				AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
				ORDER BY stockmaster.stockid";
	}

	$ErrMsg = _('No stock items were returned by the SQL because');
	$DbgMsg = _('The SQL used to retrieve the searched parts was');
	$StockItemsResult = DB_query($SQL, $db, $ErrMsg, $DbgMsg);
} //isset($_POST['SearchParts'])


/* Not appropriate really to restrict search by date since user may miss older ouststanding orders
$OrdersAfterDate = Date("d/m/Y",Mktime(0,0,0,Date("m")-2,Date("d"),Date("Y")));
*/

if (!isset($OrderNumber) or $OrderNumber == '') {
	if (isset($SelectedSupplier)) {
		echo '<a href="' . $RootPath . '/PO_Header.php?NewOrder=Yes&amp;SupplierID=' . $SelectedSupplier . '">' . _('Add Purchase Order') . '</a>';
	} else {
		echo '<a href="' . $RootPath . '/PO_Header.php?NewOrder=Yes">' . _('Add Purchase Order') . '</a>';
	}
	echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ' . $Title . '</p>';
	echo '<table class="selection">
			<tr>
				<td>' . _('Order Number') . ': <input type="text" name="OrderNumber" autofocus="autofocus" maxlength="8" size="9" />  ' . _('Into Stock Location') . ':
				<select name="StockLocation">';

	if (!isset($_POST['DateFrom'])) {
		$DateSQL = "SELECT min(orddate) as fromdate,
							max(orddate) as todate
						FROM purchorders";
		$DateResult = DB_query($DateSQL, $db);
		$DateRow = DB_fetch_array($DateResult);
		$DateFrom = $DateRow['fromdate'];
		$DateTo = $DateRow['todate'];
	} else {
		$DateFrom = FormatDateForSQL($_POST['DateFrom']);
		$DateTo = FormatDateForSQL($_POST['DateTo']);
	}

	$sql = "SELECT loccode, locationname FROM locations";
	$resultStkLocs = DB_query($sql, $db);
	while ($myrow = DB_fetch_array($resultStkLocs)) {
		if (isset($_POST['StockLocation'])) {
			if ($myrow['loccode'] == $_POST['StockLocation']) {
				echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			} else {
				echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			}
		} elseif ($myrow['loccode'] == $_SESSION['UserStockLocation']) {
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		} else {
			echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		}
	}
	echo '</select> ' . _('Order Status:') . ' <select name="Status">';
	if (!isset($_POST['Status']) OR $_POST['Status'] == 'Pending_Authorised') {
		echo '<option selected="selected" value="Pending_Authorised">' . _('Pending and Authorised') . '</option>';
	} else {
		echo '<option value="Pending_Authorised">' . _('Pending and Authorised') . '</option>';
	}
	if(isset($_POST['Status'])){
		if ($_POST['Status'] == 'Pending') {
			echo '<option selected="selected" value="Pending">' . _('Pending') . '</option>';
		} else {
			echo '<option value="Pending">' . _('Pending') . '</option>';
		}
		if ($_POST['Status'] == 'Authorised') {
			echo '<option selected="selected" value="Authorised">' . _('Authorised') . '</option>';
		} else {
			echo '<option value="Authorised">' . _('Authorised') . '</option>';
		}
		if ($_POST['Status'] == 'Cancelled') {
			echo '<option selected="selected" value="Cancelled">' . _('Cancelled') . '</option>';
		} else {
			echo '<option value="Cancelled">' . _('Cancelled') . '</option>';
		}
		if ($_POST['Status'] == 'Rejected') {
			echo '<option selected="selected" value="Rejected">' . _('Rejected') . '</option>';
		} else {
			echo '<option value="Rejected">' . _('Rejected') . '</option>';
		}
	}
	echo '</select>
		' . _('Orders Between') . ':&nbsp;
			<input type="text" name="DateFrom" value="' . ConvertSQLDate($DateFrom) . '"  class="date" size="10" alt="' . $_SESSION['DefaultDateFormat'] . '"  />
		' . _('and') . ':&nbsp;
			<input type="text" name="DateTo" value="' . ConvertSQLDate($DateTo) . '"  class="date" size="10" alt="' . $_SESSION['DefaultDateFormat'] . '"  />
		<input type="submit" name="SearchOrders" value="' . _('Search Purchase Orders') . '" />
		</td>
		</tr>
		</table>';
} //!isset($OrderNumber) or $OrderNumber == ''

$SQL = "SELECT categoryid, categorydescription FROM stockcategory ORDER BY categorydescription";
$result1 = DB_query($SQL, $db);

echo '<br /><div class="page_help_text">' . _('To search for purchase orders for a specific part use the part selection facilities below') . '</div>';
echo '<br />
		<table class="selection">
		<tr>';

echo '<td>' . _('Select a stock category') . ':
		<select name="StockCat">';

while ($myrow1 = DB_fetch_array($result1)) {
	if (isset($_POST['StockCat']) and $myrow1['categoryid'] == $_POST['StockCat']) {
		echo '<option selected="selected" value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
	}
} //end loop through categories
echo '</select></td>';
echo '<td>' . _('Enter text extracts in the') .' '. '<b>' . _('description') . '</b>:</td>';
echo '<td><input type="text" name="Keywords" size="20" maxlength="25" /></td>
		</tr>
		<tr><td></td>';
echo '<td><b>' . _('OR').' '. '</b>' . _('Enter extract of the') .' '. '<b>' . _('Stock Code') . '</b>:</td>';
echo '<td><input type="text" name="StockCode" size="15" maxlength="18" /></td>
	</tr>
	</table>
	<br />';
echo '<table>
		<tr>
			<td><input type="submit" name="SearchParts" value="' . _('Search Parts Now') . '" />
				<input type="submit" name="ResetPart" value="' . _('Show All') . '" /></td>
		</tr>
	</table>';

echo '<br />';

if (isset($StockItemsResult)) {
	echo '<table cellpadding="2" class="selection">
		<tr>
			<th class="ascending">' . _('Code') . '</th>
			<th class="ascending">' . _('Description') . '</th>
			<th class="ascending">' . _('On Hand') . '</th>
			<th class="ascending">' . _('Orders') . '<br />' . _('Outstanding') . '</th>
			<th class="ascending">' . _('Units') . '</th>
		</tr>';
		
	$k = 0; //row colour counter

	while ($myrow = DB_fetch_array($StockItemsResult)) {
		if ($k == 1) {
			echo '<tr class="EvenTableRows">';
			$k = 0;
		} //$k == 1
		else {
			echo '<tr class="OddTableRows">';
			$k = 1;
		}

		printf('<td><input type="submit" name="SelectedStockItem" value="%s"</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td>%s</td></tr>',
				$myrow['stockid'],
				$myrow['description'],
				$myrow['qoh'],
				$myrow['qord'],
				$myrow['units']);
	} //end of while loop through search items

	echo '</table>';

} //end if stock search results to show
else {
	//figure out the SQL required from the inputs available

	if (!isset($_POST['Status']) OR $_POST['Status'] == 'Pending_Authorised') {
		$StatusCriteria = " AND (purchorders.status='Pending' OR purchorders.status='Authorised' OR purchorders.status='Printed') ";
	} elseif ($_POST['Status'] == 'Authorised') {
		$StatusCriteria = " AND (purchorders.status='Authorised' OR purchorders.status='Printed')";
	} elseif ($_POST['Status'] == 'Pending') {
		$StatusCriteria = " AND purchorders.status='Pending' ";
	} elseif ($_POST['Status'] == 'Rejected') {
		$StatusCriteria = " AND purchorders.status='Rejected' ";
	} elseif ($_POST['Status'] == 'Cancelled') {
		$StatusCriteria = " AND purchorders.status='Cancelled' ";
	}
	if (isset($OrderNumber) AND $OrderNumber != '') {
		$SQL = "SELECT purchorders.orderno,
						purchorders.realorderno,
						suppliers.suppname,
						purchorders.orddate,
						purchorders.deliverydate,
						purchorders.initiator,
						purchorders.status,
						purchorders.requisitionno,
						purchorders.allowprint,
						suppliers.currcode,
						currencies.decimalplaces AS currdecimalplaces,
						SUM(purchorderdetails.unitprice*purchorderdetails.quantityord) AS ordervalue
				FROM purchorders INNER JOIN purchorderdetails
				ON purchorders.orderno=purchorderdetails.orderno
				INNER JOIN suppliers
				ON purchorders.supplierno = suppliers.supplierid
				INNER JOIN currencies
				ON suppliers.currcode=currencies.currabrev
				WHERE purchorderdetails.completed=0
				AND orddate>='" . $DateFrom . "'
				AND orddate<='" . $DateTo . "'
				AND purchorders.orderno='" . $OrderNumber . "'
				GROUP BY purchorders.orderno ASC,
					suppliers.suppname,
					purchorders.orddate,
					purchorders.status,
					purchorders.initiator,
					purchorders.requisitionno,
					purchorders.allowprint,
					suppliers.currcode";
	} else {
		//$OrderNumber is not set
		if (isset($SelectedSupplier)) {
			if (!isset($_POST['StockLocation'])) {
				$_POST['StockLocation'] = $_SESSION['UserStockLocation'];
			}

			if (isset($SelectedStockItem)) {
				$SQL = "SELECT purchorders.realorderno,
							purchorders.orderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.deliverydate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces AS currdecimalplaces,
							SUM(purchorderdetails.unitprice*purchorderdetails.quantityord) AS ordervalue
						FROM purchorders INNER JOIN purchorderdetails
						ON purchorders.orderno = purchorderdetails.orderno
						INNER JOIN suppliers
						ON  purchorders.supplierno = suppliers.supplierid
						INNER JOIN currencies
						ON suppliers.currcode=currencies.currabrev
						WHERE purchorderdetails.completed=0
						AND orddate>='" . $DateFrom . "'
						AND orddate<='" . $DateTo . "'
						AND purchorderdetails.itemcode='" . $SelectedStockItem . "'
						AND purchorders.supplierno='" . $SelectedSupplier . "'
						AND purchorders.intostocklocation = '" . $_POST['StockLocation'] . "'
						" . $StatusCriteria . "
						GROUP BY purchorders.orderno ASC,
							purchorders.realorderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces";
			} else {
				$SQL = "SELECT purchorders.realorderno,
							purchorders.orderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.deliverydate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces AS currdecimalplaces,
							SUM(purchorderdetails.unitprice*purchorderdetails.quantityord) AS ordervalue
						FROM purchorders INNER JOIN purchorderdetails
						ON purchorders.orderno = purchorderdetails.orderno
						INNER JOIN suppliers
						ON  purchorders.supplierno = suppliers.supplierid
						INNER JOIN currencies
						ON suppliers.currcode=currencies.currabrev
						WHERE purchorderdetails.completed=0
						AND orddate>='" . $DateFrom . "'
						AND orddate<='" . $DateTo . "'
						AND purchorders.supplierno='" . $SelectedSupplier . "'
						AND purchorders.intostocklocation = '" . $_POST['StockLocation'] . "'
						" . $StatusCriteria . "
						GROUP BY purchorders.orderno ASC,
							purchorders.realorderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces";
			}
		} //isset($SelectedSupplier)
		else { //no supplier selected
			if (!isset($_POST['StockLocation'])) {
				$_POST['StockLocation'] = $_SESSION['UserStockLocation'];
			}
			if (isset($SelectedStockItem) AND isset($_POST['StockLocation'])) {
				$SQL = "SELECT purchorders.realorderno,
							purchorders.orderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.deliverydate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces AS currdecimalplaces,
							SUM(purchorderdetails.unitprice*purchorderdetails.quantityord) AS ordervalue
						FROM purchorders INNER JOIN purchorderdetails
						ON purchorders.orderno = purchorderdetails.orderno
						INNER JOIN suppliers
						ON  purchorders.supplierno = suppliers.supplierid
						INNER JOIN currencies
						ON suppliers.currcode=currencies.currabrev
						WHERE purchorderdetails.completed=0
						AND orddate>='" . $DateFrom . "'
						AND orddate<='" . $DateTo . "'
						AND purchorderdetails.itemcode='" . $SelectedStockItem . "'
						AND purchorders.intostocklocation = '" . $_POST['StockLocation'] . "'
						" . $StatusCriteria . "
						GROUP BY purchorders.orderno ASC,
							purchorders.realorderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces";
			} else {
				$SQL = "SELECT purchorders.realorderno,
							purchorders.orderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.deliverydate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces AS currdecimalplaces,
							SUM(purchorderdetails.unitprice*purchorderdetails.quantityord) AS ordervalue
						FROM purchorders INNER JOIN purchorderdetails
						ON purchorders.orderno = purchorderdetails.orderno
						INNER JOIN suppliers
						ON  purchorders.supplierno = suppliers.supplierid
						INNER JOIN currencies
						ON suppliers.currcode=currencies.currabrev
						WHERE purchorderdetails.completed=0
						AND orddate>='" . $DateFrom . "'
						AND orddate<='" . $DateTo . "'
						AND purchorders.intostocklocation = '" . $_POST['StockLocation'] . "'
						" . $StatusCriteria . "
						GROUP BY purchorders.orderno ASC,
							purchorders.realorderno,
							suppliers.suppname,
							purchorders.orddate,
							purchorders.status,
							purchorders.initiator,
							purchorders.requisitionno,
							purchorders.allowprint,
							suppliers.currcode,
							currencies.decimalplaces";
			}
		} //end selected supplier
	} //end not order number selected

	$ErrMsg = _('No orders were returned by the SQL because');
	$PurchOrdersResult = DB_query($SQL, $db, $ErrMsg);

	/*show a table of the orders returned by the SQL */

	echo '<table cellpadding="2" width="97%" class="selection">';


	echo '<tr>
			<th class="ascending">' . _('Order #') . '</th>
			<th class="ascending">' . _('Order Date') . '</th>
			<th class="ascending">' . _('Delivery Date') . '</th>
			<th class="ascending">' . _('Initiated by') . '</th>
			<th class="ascending">' . _('Supplier') . '</th>
			<th class="ascending">' . _('Currency') . '</th>';

	if (in_array($PricesSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PricesSecurity)) {
		echo '<th class="ascending">' . _('Order Total') . '</th>';
	}
	echo '<th class="ascending">' . _('Status') . '</th>
			<th>' . _('Print') . '</th>
			<th>' . _('Receive') . '</th>
		</tr>';
	$j = 1;
	$k = 0; //row colour counter
	while ($myrow = DB_fetch_array($PurchOrdersResult)) {
		if ($k == 1) {
			/*alternate bgcolour of row for highlighting */
			echo '<tr class="EvenTableRows">';
			$k = 0;
		} //$k == 1
		else {
			echo '<tr class="OddTableRows">';
			$k++;
		}

		$ModifyPage = $RootPath . '/PO_Header.php?ModifyOrderNumber=' . $myrow['orderno'];
		if ($myrow['status'] == 'Printed') {
			$ReceiveOrder = '<a href="' . $RootPath . '/GoodsReceived.php?PONumber=' . $myrow['orderno'] . '">' . _('Receive') . '</a>';
		} else {
			$ReceiveOrder = '';
		}
		if ($myrow['status'] == 'Authorised' AND $myrow['allowprint'] == 1) {
			$PrintPurchOrder = '<a target="_blank" href="' . $RootPath . '/PO_PDFPurchOrder.php?OrderNo=' . $myrow['orderno'] . '">' . _('Print') . '</a>';
		} elseif ($myrow['status'] == 'Authorisied' AND $myrow['allowprint'] == 0) {
			$PrintPurchOrder = _('Printed');
		} elseif ($myrow['status'] == 'Printed') {
			$PrintPurchOrder = '<a target="_blank" href="' . $RootPath . '/PO_PDFPurchOrder.php?OrderNo=' . $myrow['orderno'] . '&amp;realorderno=' . $myrow['realorderno'] . '&amp;ViewingOnly=2">
				' . _('Print Copy') . '</a>';
		} else {
			$PrintPurchOrder = _('N/A');
		}


		$FormatedOrderDate = ConvertSQLDate($myrow['orddate']);
		$FormatedDeliveryDate = ConvertSQLDate($myrow['deliverydate']);
		$FormatedOrderValue = locale_number_format($myrow['ordervalue'], $myrow['currdecimalplaces']);
		$sql = "SELECT realname FROM www_users WHERE userid='" . $myrow['initiator'] . "'";
		$UserResult = DB_query($sql, $db);
		$MyUserRow = DB_fetch_array($UserResult);
		$InitiatorName = $MyUserRow['realname'];

		echo '<td><a href="' . $ModifyPage . '">' . $myrow['orderno'] . '</a></td>
			<td>' . $FormatedOrderDate . '</td>
			<td>' . $FormatedDeliveryDate . '</td>
			<td>' . $InitiatorName . '</td>
			<td>' . $myrow['suppname'] . '</td>
			<td>' . $myrow['currcode'] . '</td>';
		if (in_array($PricesSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PricesSecurity)) {
			echo '<td class="number">' . $FormatedOrderValue . '</td>';
		}
		echo '<td>' . _($myrow['status']) . '</td>
				<td>' . $PrintPurchOrder . '</td>
				<td>' . $ReceiveOrder . '</td>
			</tr>';
	} //end of while loop around purchase orders retrieved

	echo '</table>';
}
echo '</div>
      </form>';
include('includes/footer.inc');
?>