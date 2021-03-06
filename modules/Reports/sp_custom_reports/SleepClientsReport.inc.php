<?php
// This is the SPConfiguration autogenerated custom report method file

class SleepClientsReport extends AbstractCustomReportModel {

    public $sqlDate;
    public $printDate;

    public function getChartsViewControlData() {
        return array(
            Reports_CustomReportTypes_Model::TABLE => array(),
        );
    }

    protected function getCalculationData($outputFormat = 'PDF') {
        global $adb;

        $date = $this->getStartDate();
        $contactsArray = $this->getWhereExclude($adb);
        $dataResult = array();

        $so_history = "SELECT * FROM vtiger_contactdetails INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid INNER JOIN vtiger_contactaddress ON vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid INNER JOIN (SELECT SUM(total) as totalsum, vtiger_salesorder.contactid, vtiger_salesordercf.cf_1269, vtiger_salesorder.cf_assets_id FROM vtiger_salesorder INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_salesorder.salesorderid INNER JOIN vtiger_salesordercf ON vtiger_salesorder.salesorderid = vtiger_salesordercf.salesorderid WHERE vtiger_crmentity.deleted = 0 GROUP BY vtiger_salesorder.contactid ORDER BY vtiger_salesordercf.cf_1269 DESC) b ON vtiger_contactdetails.contactid = b.contactid WHERE vtiger_contactdetails.contactid NOT IN (" . generateQuestionMarks($contactsArray) . ")";
        $historyResult = $adb->pquery($so_history, $contactsArray);
        $noOfOrders = $adb->num_rows($historyResult);
        for ($i = 0; $i < $noOfOrders; $i++) {
            $dataResult[] = [
                $adb->query_result($historyResult, $i, 'firstname'),
                $adb->query_result($historyResult, $i, 'lastname'),
                $adb->query_result($historyResult, $i, 'phone'),
                $adb->query_result($historyResult, $i, 'contact_no'),
                $adb->query_result($historyResult, $i, 'mailingstreet'),
                Vtiger_Date_UIType::getDisplayDateValue($adb->query_result($historyResult, $i, 'cf_1269')),
                Vtiger_Currency_UIType::transformDisplayValue($adb->query_result($historyResult, $i, 'totalsum')),
                Vtiger_Util_Helper::getRecordName($adb->query_result($historyResult, $i, 'cf_assets_id')),
                "<a href='index.php?module=Contacts&view=Detail&record=" . $adb->query_result($historyResult, $i, 'contactid') . "&app=INVENTORY'>Просмотр</a>"
            ];
        }

        if($this->getViewTypeName() === Reports_CustomReportTypes_Model::TABLE) {
            return $dataResult;
        }
    }

    public function hasLastLinkColumn() {
        return true;
    }

    private function getStartDate()
    {
        $conditions = $this->getFiltersConditions();
        $columns = $conditions[1]['columns'];
        $date = date("d-m-Y",strtotime("-1 month"));

        if (empty($columns)) {
            return $this->formatAndSetDate($date);
        } else {
            foreach ($columns as $column) {
                if ($column['columnname'] == 'vtiger_salesordercf:cf_1269:SalesOrder_День_доставки:cf_1269:D') {
                    return $this->formatAndSetDate($column['value']);
                }
            }
        }
        return $this->formatAndSetDate($date);
    }

    private function formatAndSetDate($date)
    {
        $dateObject = DateTime::createFromFormat('d-m-Y', $date);
        $this->printDate = $date;
        $this->sqlDate = $dateObject->format('Y-m-d');
        return $this->sqlDate;
    }


    private function getWhereExclude($adb)
    {
        $result = array();
        $contactList = "SELECT contactid FROM vtiger_salesorder INNER JOIN vtiger_crmentity ON vtiger_salesorder.salesorderid = vtiger_crmentity.crmid INNER JOIN vtiger_salesordercf ON vtiger_salesorder.salesorderid = vtiger_salesordercf.salesorderid WHERE vtiger_crmentity.deleted = ? AND vtiger_salesordercf.cf_1269 > ? GROUP BY contactid";
        $startResult = $adb->pquery($contactList, array(0, $this->sqlDate));

        $noOfContacts = $adb->num_rows($startResult);
        for ($i = 0; $i < $noOfContacts; $i++) {
            $result[] = $adb->query_result($startResult, $i, 'contactid');
        }
        return $result;
    }

    protected function getLabels($outputFormat = 'PDF') {
        return ["Имя", "Фамилия", "Телефон", "Номер", "Улица", "Последняя дата доставки", "Сумма заказов", "Актив"];
    }

}

?>
