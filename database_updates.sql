-- Add gender column to Users table
ALTER TABLE Users ADD COLUMN gender ENUM('male', 'female') NOT NULL;

-- Create Money History table
CREATE TABLE IF NOT EXISTS MoneyHistory (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
); 