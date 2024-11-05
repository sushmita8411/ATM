// File name: OptionMenu.java
import java.sql.Connection;
import java.sql.SQLException;
import java.util.Scanner;
import java.sql.PreparedStatement;


public class OptionMenu {
    private Account account;
    private Scanner input = new Scanner(System.in);
    private Connection conn;

    public OptionMenu(Account account, Connection conn) {
        this.account = account;
        this.conn = conn;
    }

    public void getAccountType() {
        while (true) {
            System.out.println("Select the Account you want to access: ");
            System.out.println("1 - View Balance");
            System.out.println("2 - Withdraw Funds");
            System.out.println("3 - Deposit Funds");
            System.out.println("4 - Change PIN");
            System.out.println("5 - Exit");
            System.out.print("Choice: ");

            int choice = input.nextInt();
            try {
                switch (choice) {
                    case 1 -> viewBalance();
                    case 2 -> withdrawFunds();
                    case 3 -> depositFunds();
                    case 4 -> changePin();
                    case 5 -> {
                        System.out.println("Thank you for using our service. Goodbye!");
                        return;
                    }
                    default -> System.out.println("Invalid choice. Please try again.");
                }
            } catch (SQLException e) {
                System.err.println("Database error: " + e.getMessage());
            }
        }
    }

    private void viewBalance() {
        System.out.println("Current Balance: " + account.getBalance());
    }

    private void withdrawFunds() throws SQLException {
        System.out.print("Enter amount to withdraw: ");
        double amount = input.nextDouble();
        if (account.withdraw(conn, amount)) {
            System.out.println("Withdrawal successful! New balance: " + account.getBalance());
        } else {
            System.out.println("Insufficient funds.");
        }
    }

    private void depositFunds() throws SQLException {
        System.out.print("Enter amount to deposit: ");
        double amount = input.nextDouble();
        account.deposit(conn, amount);
        System.out.println("Deposit successful! New balance: " + account.getBalance());
    }

    private void changePin() {
        System.out.print("Enter new 4-digit PIN: ");
        int newPin = input.nextInt();
        
        System.out.print("Re-enter new 4-digit PIN for confirmation: ");
        int confirmPin = input.nextInt();

        if (newPin == confirmPin) {
            // Here, you would typically also update the PIN in the database
            // This might require an additional method in the Account class
            System.out.println("PIN changed successfully!");
            // Assume there is a method in Account class to update the PIN in the database
            try {
                updatePinInDatabase(newPin);
            } catch (SQLException e) {
                System.err.println("Error updating PIN in database: " + e.getMessage());
            }
        } else {
            System.out.println("PINs do not match. Please try again.");
        }
    }

    private void updatePinInDatabase(int newPin) throws SQLException {
        String updatePinSQL = "UPDATE accounts SET pin = ? WHERE account_id = ?";
        try (PreparedStatement pstmt = conn.prepareStatement(updatePinSQL)) {
            pstmt.setInt(1, newPin);
            pstmt.setInt(2, account.getAccountId()); // Assuming you have a method to get account ID
            pstmt.executeUpdate();
        }
    }
}
