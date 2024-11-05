import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Random;
import java.util.Scanner;
public class Signup {
   private Scanner scanner = new Scanner(System.in);
   private Random random = new Random();
   public void getSignup(Connection conn) {
       System.out.println("Creating a new account...");
       System.out.print("Enter Username: ");
       String username = scanner.next();
       System.out.print("Enter Password: ");
       String password = scanner.next();
       // Create new user and save to the users table
       int userId = createUser(conn, username, password);
       if (userId != -1) {
           // Generate a random account number
           int accountNumber = generateUniqueAccountNumber(conn);
           if (accountNumber != -1 && createAccount(conn, userId, accountNumber)) {
               System.out.println("Account created successfully! Your account number is: " + accountNumber);
           } else {
               System.out.println("Account creation failed. Please try again.");
           }
       } else {
           System.out.println("Account creation failed. Username might already exist.");
       }
   }
   private int createUser(Connection conn, String username, String password) {
       String sql = "INSERT INTO users (username, password) VALUES (?, ?)";
       try (PreparedStatement pstmt = conn.prepareStatement(sql, PreparedStatement.RETURN_GENERATED_KEYS)) {
           pstmt.setString(1, username);
           pstmt.setString(2, password); // Store the password (consider hashing it in a real application)
           pstmt.executeUpdate();
           // Retrieve the generated user_id
           ResultSet generatedKeys = pstmt.getGeneratedKeys();
           if (generatedKeys.next()) {
               return generatedKeys.getInt(1); // Return the user_id
           }
       } catch (SQLException e) {
           e.printStackTrace();
       }
       return -1; // User creation failed
   }
   private int generateUniqueAccountNumber(Connection conn) {
       int accountNumber;
       boolean unique = false;
       do {
           accountNumber = 100000 + random.nextInt(900000); // Generate a random 6-digit number
           // Check if the account number is unique
           String sql = "SELECT COUNT(*) FROM Accounts WHERE account_number = ?";
           try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
               pstmt.setInt(1, accountNumber);
               ResultSet rs = pstmt.executeQuery();
               if (rs.next() && rs.getInt(1) == 0) {
                   unique = true; // Account number is unique
               }
           } catch (SQLException e) {
               e.printStackTrace();
           }
       } while (!unique);
       return accountNumber; // Return the unique account number
   }
   private boolean createAccount(Connection conn, int userId, int accountNumber) {
       String sql = "INSERT INTO Accounts (user_id, account_number, pin, balance) VALUES (?, ?, ?, ?)";
       try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
           pstmt.setInt(1, userId);
           pstmt.setInt(2, accountNumber);
           pstmt.setInt(3, getPin()); // Get PIN from the user or generate it securely
           pstmt.setDouble(4, 0.0); // Initial balance is set to 0.0
           pstmt.executeUpdate();
           return true; // Account created successfully
       } catch (SQLException e) {
           e.printStackTrace();
           return false; // Account creation failed
       }
   }
   private int getPin() {
       System.out.print("Enter a 4-digit PIN: ");
       return scanner.nextInt(); // Alternatively, you could implement validation for PIN here
   }
}
