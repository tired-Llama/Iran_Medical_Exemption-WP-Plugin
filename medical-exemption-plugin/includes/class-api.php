<?php
class MedicalExemption_API {
    
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    public function register_routes() {
        // Search CSV data
        register_rest_route('medical-exemption/v1', '/search', array(
            'methods' => 'POST',
            'callback' => array($this, 'search_exemptions'),
            'permission_callback' => '__return_true',
        ));
        
        // Submit form data
        register_rest_route('medical-exemption/v1', '/submit', array(
            'methods' => 'POST',
            'callback' => array($this, 'submit_exemption'),
            'permission_callback' => '__return_true',
        ));
    }
    
    // Search CSV data
    public function search_exemptions($request) {
        $params = $request->get_json_params();
        $date = sanitize_text_field($params['date'] ?? '');
        $section = sanitize_text_field($params['section'] ?? '');
        $article = sanitize_text_field($params['article'] ?? '');
        
        $results = $this->read_csv_data($date, $section, $article);
        
        return rest_ensure_response(array(
            'success' => true,
            'data' => $results
        ));
    }
    
    // Submit to database
    public function submit_exemption($request) {
        global $wpdb;
        
        $params = $request->get_json_params();
        $table_name = $wpdb->prefix . 'medical_exemption';
        
        $result = $wpdb->insert($table_name, array(
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'request_time' => current_time('mysql'),
            'medical_exemption_date' => sanitize_text_field($params['date'] ?? ''),
            'medical_exemption_section' => sanitize_text_field($params['section'] ?? ''),
            'medical_exemption_article' => sanitize_text_field($params['article'] ?? ''),
        ));
        
        if ($result === false) {
            return new WP_Error('db_error', 'Failed to save data', array('status' => 500));
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Data saved successfully'
        ));
    }
    
    // Read and filter CSV data
    private function read_csv_data($date, $section, $article) {
        $csv_file = MEDICAL_EXEMPTION_DIR . 'assets/DataBase.csv';
        
        if (!file_exists($csv_file)) {
            return array();
        }
        
        $database = fopen($csv_file, 'r');
        fgetcsv($database, 10000, ','); // Skip header
        
        $results = array();
        $num = 0;
        
        // Parse date
        $input_year = $input_month = $input_day = null;
        if (!empty($date)) {
            $date_parts = explode('/', $date);
            if (count($date_parts) === 3) {
                list($input_year, $input_month, $input_day) = $date_parts;
            }
        }
        
        while ($line = fgetcsv($database, 10000, ',')) {
            $year_filter = true;
            $section_filter = true;
            $article_filter = true;
            
            // Year filter logic (from your original code)
            if (!empty($date) && $input_year !== null) {
                $year = $line[2];
                
                if (($input_year >= 1374 && $input_month >= 2 && $input_day >= 22) && 
                    ($input_year <= 1376 && $input_month <= 2 && $input_day <= 22)) {
                    $year_filter = ($year == "1364" || $year == "1375");
                } else if (($input_year >= 1381 && $input_month >= 10 && $input_day >= 21) && 
                           ($input_year <= 1383 && $input_month <= 10 && $input_day <= 21)) {
                    $year_filter = ($year == "1375" || $year == "1382");
                } else if (($input_year >= 1392 && $input_month >= 3 && $input_day >= 23) && 
                           ($input_year <= 1394 && $input_month <= 3 && $input_day <= 23)) {
                    $year_filter = ($year == "1382" || $year == "1393");
                } else if ($input_year <= 1374 && $input_month <= 2 && $input_day <= 22) {
                    $year_filter = ($year == "1364");
                } else if ($input_year <= 1381 && $input_month <= 10 && $input_day <= 21) {
                    $year_filter = ($year == "1375");
                } else if ($input_year <= 1392 && $input_month <= 3 && $input_day <= 23) {
                    $year_filter = ($year == "1382");
                } else {
                    $year_filter = ($year == "1393");
                }
            } else {
                $year_filter = empty($date);
            }
            
            // Section filter
            if (!empty($section)) {
                $section_filter = ($section == $line[4]) || (strpos($line[3], $section) !== false);
            }
            
            // Article filter
            if (!empty($article)) {
                $article_filter = ($article == $line[7]);
            }
            
            // Apply filters
            $filter_var = true;
            if (!empty($date)) $filter_var = $filter_var && $year_filter;
            if (!empty($section)) $filter_var = $filter_var && $section_filter;
            if (!empty($article)) $filter_var = $filter_var && $article_filter;
            
            if ($filter_var && (!empty($date) || !empty($section) || !empty($article))) {
                $num++;
                $results[] = array(
                    'num' => $num,
                    'priority' => $line[1] ?? '',
                    'year' => $line[2] ?? '',
                    'section_name' => $line[3] ?? '',
                    'section_code' => $line[4] ?? '',
                    'article' => $line[7] ?? '',
                    'subject' => $line[6] ?? '',
                    'summary' => $line[8] ?? '',
                    'description' => $line[9] ?? '',
                );
            }
        }
        
        fclose($database);
        return $results;
    }
}

new MedicalExemption_API();