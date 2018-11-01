    <?php 
class Users
{
    public function getCategoryListing($language) {
        $terms = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => false,
        ));
        $admin_email = get_option('qtranslate_term_name');
        
        $arrayPrep = array();
        foreach ($terms as $key => $term) {
            $langs = array();
            $text = apply_filters('translate_text', $term->description, $lang = $language, $flags = 0);
            $arrayPrep[] = array("categoryID" => $term->term_id , "name" => apply_filters('translate_text', $term->name, $lang = $language, $flags = 0) ,"description" => $text);
        }
        return $arrayPrep;
        
    }
}
?>