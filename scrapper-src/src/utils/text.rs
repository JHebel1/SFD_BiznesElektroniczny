pub fn clean_text(a: &scraper::ElementRef<'_>) -> String {
    a.text()
        .collect::<String>()
        .trim()
        .replace('\n', " ")
        .replace(char::is_whitespace, " ")
        .trim()
        .to_string()
}

pub fn clean_text_html(a: &String) -> String {
    a.trim()
        .replace('\n', " ")
        .replace(char::is_whitespace, " ")
        .split_whitespace()
        .collect::<Vec<_>>()
        .join(" ")
}