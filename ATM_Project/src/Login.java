// File name: Login.java
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Scanner;
public class Login {
   private Scanner menuInput = new Scanner(System.in);
   public void getLogin(Connection conn) {
       while (true) {
           System.out.print("Enter Username: ");
           String username = menuInput.next();
           System.out.print("Enter Password: ");
           String password = menuInput.next();
           int userId = validateUser(conn, username, password);
           if (userId != -1) {
               System.out.println("Login successful!");
               Account account = null;
				try {
					account = Account.loadAccount(conn, username);
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
              
               if (account != null) {
                   System.out.print("Enter your PIN to access the account: ");
                   int enteredPin = menuInput.nextInt();
                   if (account.getPin() == enteredPin) {
                       new OptionMenu(account, conn).getAccountType();
                   } else {
                       System.out.println("Incorrect PIN. Access denied.");
                   }
               } else {
                   System.out.println("No account found for this user.");
               }
               break; // Exit the login loop
           } else {
               System.out.println("Invalid username or password. Would you like to try again or sign up?");
               System.out.println("1. Try again\n2. Sign up");
               int choice = menuInput.nextInt();
               if (choice == 2) {
                   new Signup().getSignup(conn);
                   break;
               }
           }
       }
   }
   private int validateUser(Connection conn, String username, String password) {
       String sql = "SELECT user_id FROM Users WHERE username = ? AND password = ?";
       try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
           pstmt.setString(1, username);
           pstmt.setString(2, password);
           ResultSet rs = pstmt.executeQuery();
           if (rs.next()) {
               return rs.getInt("user_id"); // Return user ID if found
           }
       } catch (SQLException e) {
           System.err.println("SQL error during user validation: " + e.getMessage());
       }
       return -1; // No matching user found
   }
}
