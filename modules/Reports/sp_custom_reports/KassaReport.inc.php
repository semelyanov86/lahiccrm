<?php
// This is the SPConfiguration autogenerated custom report method file


class KassaReport extends AbstractCustomReportModel {

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
        $ostatok_start = $this->getStartOstatok($adb);
        $total_prihod = 0;
        $total_rashod = 0;

        $dataResult = array(['Kassa', Vtiger_Currency_UIType::transformDisplayValue($ostatok_start), '', '', '', '']);

        $payment_history = "SELECT * FROM `sp_payments` INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = sp_payments.payid WHERE sp_payments.pay_date = ? AND vtiger_crmentity.deleted = ?";
        $historyResult = $adb->pquery($payment_history, array($this->sqlDate, 0));
        $noOfPayments = $adb->num_rows($historyResult);
        for ($i = 0; $i < $noOfPayments; $i++) {
            if ($adb->query_result($historyResult, $i, 'pay_type') == 'Receipt') {
                $prihod = $adb->query_result($historyResult, $i, 'amount');
                $total_prihod += $prihod;
                $rashod = '';
            } else {
                $rashod = $adb->query_result($historyResult, $i, 'amount');
                $total_rashod += $rashod;
                $prihod = '';
            }
            $dataResult[] = [$adb->query_result($historyResult, $i, 'pay_details'), '', Vtiger_Currency_UIType::transformDisplayValue($prihod), Vtiger_Currency_UIType::transformDisplayValue($rashod), '', "<a href='index.php?module=SPPayments&view=Detail&record=" . $adb->query_result($historyResult, $i, 'payid') . "&app=INVENTORY'>Просмотр</a>"];
        }
        $ostatok_end = $ostatok_start + $total_prihod - $total_rashod;
        $dataResult[] = ['Total', '', Vtiger_Currency_UIType::transformDisplayValue($total_prihod), Vtiger_Currency_UIType::transformDisplayValue($total_rashod), Vtiger_Currency_UIType::transformDisplayValue($ostatok_end), ''];

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
        $date = date('d-m-Y');
        if (empty($columns)) {
            return $this->formatAndSetDate($date);
        } else {
            foreach ($columns as $column) {
                if ($column['columnname'] == 'sp_payments:pay_date:SPPayments_Pay_date:pay_date:D') {
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


    private function getStartOstatok($adb)
    {
        $start_ostatok = "SELECT * FROM `sp_payments` INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = sp_payments.payid WHERE sp_payments.pay_date < ? AND vtiger_crmentity.deleted = ?";
        $startResult = $adb->pquery($start_ostatok, array($this->sqlDate, 0));

        $ostatok_start = 0;

        $noOfPayments = $adb->num_rows($startResult);
        for ($i = 0; $i < $noOfPayments; $i++) {
            if ($adb->query_result($startResult, $i, 'pay_type') == 'Receipt') {
                $ostatok_start += $adb->query_result($startResult, $i, 'amount');
            } else {
                $ostatok_start -= $adb->query_result($startResult, $i, 'amount');
            }
        }
        return $ostatok_start;
    }

    protected function getLabels($outputFormat = 'PDF') {
        return ["Основание", "Остаток на начало дня", "Приход", "Расход", "Остаток на конец"];
    }

    /**
     * Returns data which need to print on same action
     * @return string
     */
    public function getPrintData() {
        $result = $this->getData();

        $viewer = new Vtiger_Viewer();
        $viewer->assign('COLUMN_NAMES', array_keys(reset($result['data'])));
        $viewer->assign('DATA', $result['data']);
        $viewer->assign('CUSTOM_REPORT', $this);
        $viewer->assign('PRINT_DATE', $this->printDate);
        $viewer->assign('COMPANY_INFO', Settings_Vtiger_CompanyDetails_Model::getInstance());

        $printOutput = array();
        $printOutput[] = $viewer->view('sp_custom_reports/KassaDefaultPrint.tpl', $this->vtigerReportModel->getModuleName(), true);
        $printOutput[] = $this->getCount();
        return $printOutput;
    }

}

?>
