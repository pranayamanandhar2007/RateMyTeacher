USE RateMyTeacher;

CREATE TABLE IF NOT EXISTS rating_helpful_votes (
    r_id INT NOT NULL,
    s_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (r_id, s_id),
    FOREIGN KEY (r_id) REFERENCES ratings(r_id) ON DELETE CASCADE,
    FOREIGN KEY (s_id) REFERENCES students(s_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS rating_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    r_id INT NOT NULL,
    s_id INT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rating_reporter (r_id, s_id),
    FOREIGN KEY (r_id) REFERENCES ratings(r_id) ON DELETE CASCADE,
    FOREIGN KEY (s_id) REFERENCES students(s_id) ON DELETE CASCADE
);
