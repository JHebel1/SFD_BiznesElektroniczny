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

pub fn escape_csv_field(value: &str) -> String {
    let mut v = value.replace('"', "\"\"");
    if v.contains([',', '\n', '\r']) {
        v = format!("\"{}\"", v);
    }
    v
}