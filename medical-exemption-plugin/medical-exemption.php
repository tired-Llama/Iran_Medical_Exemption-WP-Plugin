<div class="exemption-container">
  <form action="" method="post" class="exemption-form">
    <div class="form-item">
      <label>تاریخ معافیت</label>
      <input 
        type="text" 
        name="medical_exemption_date" 
        id="medical_exemption_date" 
        class="form-control"
        placeholder="انتخاب تاریخ" 
        autocomplete="off"
      >
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
      <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
      <script>
        $(function() {
          $('#medical_exemption_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            observer: true,
            initialValue: false,
            autoClose: true,
            calendar:{
              persian: {
                locale: 'fa'
              }
            }
          });
        });
      </script>
    </div>
  
    <div class="form-item">
      <label for="medical_exemption_section">ماده / بخش</label>
      <input 
        type="text" 
        name="medical_exemption_section" 
        id="medical_exemption_section"
        class="form-control"
        autocomplete="off"
      >
    </div>
    
    <div class="form-item">
      <label for="medical_exemption_article">بند</label>
      <input 
        type="text" 
        name="medical_exemption_article" 
        id="medical_exemption_article"
        class="form-control"
        autocomplete="off"
      >
    </div>
    
    <input 
      type="submit" 
      name="submit" 
      value="بررسی"
      class="submit-button"
    >
  </form>
  
  <div>
    <?php
      if (!class_exists('MedicalExemptionPlugin')) {
        class MedicalExemptionPlugin {
          function suggest_medical_exemption($date, $section, $article) {
            $database = fopen('./assets/DataBase.csv', 'r');
            fgetcsv($database, 10000, ',');
            $input_year = explode("/", $date)[0];
            $input_month = explode("/", $date)[1];
            $input_day = explode("/", $date)[2];
            // year filter
            $num = 0;
            if ($date!='' || $section != '' || $article != ''){
              while ($line = fgetcsv($database, 10000, ',')) {
                $year = $line[2];

                if (($input_year>=1374 && $input_month>=2 && $input_day>=22) && ($input_year<=1376 && $input_month<=2 && $input_day<=22)){
                  $year_filter = ($year == "1364" || $year = "1375");
                } else if (($input_year>=1381 && $input_month>=10 && $input_day>=21) && ($input_year<=1383 && $input_month<=10 && $input_day<=21)){
                  $year_filter = ($year == "1375" || $year == "1382");
                } else if (($input_year>=1392 && $input_month>=3 && $input_day>=23) && ($input_year<=1394 && $input_month<=3 && $input_day<=23)){
                  $year_filter = ($year == "1382" || $year == "1393");
                } else if ($input_year<=1374 && $input_month<=2 && $input_day<=22) {
                  $year_filter = ($year == "1364");
                } else if ($input_year<=1381 && $input_month<=10 && $input_day<=21) {
                  $year_filter = ($year == "1375");
                } else if ($input_year<=1392 && $input_month<=3 && $input_day<=23) {
                  $year_filter = ($year == "1382");
                } else {
                  $year_filter = ($year == "1393");
                }
                // $section_filter = preg_match('*' . $section . '*', $line[4]) || preg_match('*' . $section . '*', $line[3]);
                $section_filter = $section == $line[4] || preg_match('*' . $section . '*', $line[3]);
                $article_filter = $article == $line[7];
                
                if ($date!='' && $section!='' && $article!=''){
                  $filter_var = $year_filter && $section_filter && $article_filter;
                } else if ($date!='' && $section!='' && $article==''){
                  $filter_var = $year_filter && $section_filter;
                } else if ($date!='' && $section=='' && $article!=''){
                  $filter_var = $year_filter && $article_filter;
                } else if ($date=='' && $section!='' && $article!=''){
                  $filter_var = $section_filter && $article_filter;
                } else if ($date!='' && $section=='' && $article==''){
                  $filter_var = $year_filter;
                } else if ($date=='' && $section!='' && $article==''){
                  $filter_var = $section_filter;
                } else if ($date=='' && $section=='' && $article!=''){
                  $filter_var = $article_filter;
                } else if ($date=='' && $section=='' && $article==''){
                  $filter_var = false;
                }
                if ($filter_var) {
                  $num++;
                  echo "<div class='record-row'>";
                  echo "<span class='record-field'>" . $num . "</span>";
                  echo "<span class='record-field'>" . $line[1] . "</span>";
                  echo "<span class='record-field'>" . $line[2] . "</span>";
                  echo "<span class='record-field'>" . $line[3] . "</span>";
                  echo "<span class='record-field'>" . $line[4] . "</span>";
                  echo "<span class='record-field'>" . $line[7] . "</span>";
                  echo "<span class='record-field extra-wide'>" . $line[6] . "</span>";
                  echo "<span class='record-field extra-wide'>" . $line[8] . "</span>";
                  echo "<span class='record-field extra-wide'>" . $line[9] . "</span>";
                  echo "</div>";
                }
              }
            }
            fclose($database);
          }
        }
        // $medicalExemptionPlugin = new MedicalExemptionPlugin();
        // $medicalExemptionPlugin->suggest_medical_exemption("32","1");
      }
    ?>
    <div class="record-container">
      <div class="record-header">
        <span class="record-field">ردیف</span>
        <span class="record-field">اولویت انتخاب</span>
        <span class="record-field">سال</span>
        <span class="record-field">بخش</span>
        <span class="record-field">ماده</span>
        <span class="record-field">بند</span>
        <span class="record-field extra-wide">موضوع</span>
        <span class="record-field extra-wide">خلاصه</span>
        <span class="record-field extra-wide">شرح</span>
      </div>
      <?php
        if (isset($_POST['submit'])) {
          $date = $_POST['medical_exemption_date'];
          $section = $_POST['medical_exemption_section'];
          $article = $_POST['medical_exemption_article'];
          submit_medical_exemption()
        }
        $_POST = null;
        $medicalExemptionPlugin = new MedicalExemptionPlugin();
        $medicalExemptionPlugin->suggest_medical_exemption($date, $section, $article);
      ?>
    </div>
  
  </div>
</div>