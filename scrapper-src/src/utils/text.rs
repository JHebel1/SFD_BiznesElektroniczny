pub fn clean_text(a: &scraper::ElementRef<'_>) -> String {
    a.text()
        .collect::<String>()
        .trim()
        .replace('\n', " ")
        .replace(char::is_whitespace, " ")
        .trim()
        .to_string()
}