// File name: Account.java
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class Account {
    private String username;
    private int accountId; // Primary key
    private int accountNumber;
    private int pin;
    private double balance;

    // Constructor for account creation
    public Account(String username, int accountId, int accountNumber, int pin) {
        this.username = username;
        this.accountId = accountId;
        this.accountNumber = accountNumber;
        this.pin = pin;
        this.balance = 0.0;
    }

    // Getters and Setters
    public String getUsername() { return username; }
    public int getAccountId() { return accountId; }
    public int getAccountNumber() { return accountNumber; }
    public int getPin() { return pin; }
    public double getBalance() { return balance; }
    public void setBalance(double balance) { this.balance = balance; }

    // Withdraw funds and update database
    public boolean withdraw(Connection conn, double amount) throws SQLException {
        if (amount <= balance) {
            balance -= amount;
            updateBalanceInDB(conn, -amount, "withdrawal");
            return true;
        }
        return false;
    }

    // Deposit funds and update database
    public void deposit(Connection conn, double amount) throws SQLException {
        balance += amount;
        updateBalanceInDB(conn, amount, "deposit");
    }

    // Update balance in database and save transaction
    private void updateBalanceInDB(Connection conn, double amount, String transactionType) throws SQLException {
        String updateBalanceSQL = "UPDATE accounts SET balance = ? WHERE account_id = ?";
        try (PreparedStatement pstmt = conn.prepareStatement(updateBalanceSQL)) {
            pstmt.setDouble(1, balance);
            pstmt.setInt(2, accountId);
            pstmt.executeUpdate();
        }
        saveTransaction(conn, transactionType, amount);
    }

    // Save a transaction in the database
    private void saveTransaction(Connection conn, String type, double amount) throws SQLException {
        String sql = "INSERT INTO transactions (account_id, transaction_type, amount) VALUES (?, ?, ?)";
        try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, accountId);
            pstmt.setString(2, type);
            pstmt.setDouble(3, amount);
            pstmt.executeUpdate();
        }
    }

    // Load account data from database
    public static Account loadAccount(Connection conn, String username) throws SQLException {
        String sql = "SELECT a.account_id, a.account_number, a.pin, a.balance " +
                     "FROM accounts a JOIN users u ON a.user_id = u.user_id WHERE u.username = ?";
        try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setString(1, username);
            ResultSet rs = pstmt.executeQuery();
            if (rs.next()) {
                Account account = new Account(
                    username,
                    rs.getInt("account_id"),
                    rs.getInt("account_number"),
                    rs.getInt("pin")
                );
                account.setBalance(rs.getDouble("balance"));
                return account;
            }
        }
        return null;
    }
}

