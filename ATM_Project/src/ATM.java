// File name: ATM.java
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Scanner;

public class ATM {
    private static final String DB_URL = "jdbc:mysql://localhost:3306/atm";
    private static final String USER = "root";
    private static final String PASS = "1234";
    private Scanner scanner = new Scanner(System.in);
    private Connection conn;

    public static void main(String[] args) {
        ATM atm = new ATM();
        atm.connectToDatabase();
        atm.start();
    }

    private void connectToDatabase() {
        try {
            conn = DriverManager.getConnection(DB_URL, USER, PASS);
            System.out.println("Database connected successfully.");
        } catch (SQLException e) {
            System.out.println("Failed to connect to the database: " + e.getMessage());
        }
    }

    private void start() {
        while (true) {
            System.out.println("Welcome to the ATM!");
            System.out.println("1. Login");
            System.out.println("2. Sign Up");
            System.out.println("3. Exit");
            System.out.print("Select an option: ");
            int choice = scanner.nextInt();

            switch (choice) {
                case 1 -> new Login().getLogin(conn);
                case 2 -> new Signup().getSignup(conn);
                case 3 -> {
                    System.out.println("Thank you for using the ATM. Goodbye!");
                    closeConnection();
                    return;
                }
                default -> System.out.println("Invalid choice. Please try again.");
            }
        }
    }

    private void closeConnection() {
        try {
            if (conn != null && !conn.isClosed()) {
                conn.close();
                System.out.println("Database connection closed.");
            }
        } catch (SQLException e) {
            System.err.println("Error closing connection: " + e.getMessage());
        }
    }
}
