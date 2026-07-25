Title Project: **Centralized Printer Server Deployment Using ARM Microprocessor**

A centralized **IoT-based Printer Server System** that enables students to print documents from multiple devices, including laptops, smartphones, and desktop computers over a network. Before a print job is executed, users must authenticate using an **RFID Card**, providing an additional layer of security to ensure that only authorized users can access their documents.

1.1 Background

Educational institutions often face several challenges in managing printing services, including:

  1. Inefficient print job management
  2. Distributed printer administration across multiple locations
  3. Limited document security and user privacy
  4. Difficulty monitoring and reporting printer usage
  5. High implementation costs of commercial printing solutions.

To address these issues, this project introduces a **Centralized Printer Server System** powered by a Raspberry Pi, providing a secure, efficient, and cost-effective printing solution.

1.2 Features

- Role-based user authentication
- User management
- Document upload for printing
- Microsoft Word (.doc/.docx) to PDF conversion
- Token-based balance top-up system
- Automatic printing cost calculation
- RFID card authentication before printing
- Print job execution through Raspberry Pi
- Transaction history management
- Secure logout functionality.

1.3 User Roles

Role & Description
 1. **Administrator**: Manages user accounts, resets passwords, and controls account activation 
 2. **Staff**: Tops up student balances and monitors transaction history
 3. **Student**: Uploads files, converts documents, redeems balance tokens, and prints documents

1.4 System Architecture

```Start```
Student
    │
    ▼
 Laravel Web Application
    │
 RESTful API
    │
    ▼
 Raspberry Pi 4
    │
 ┌──────┴────────┐
 │               │
RFID RC522    Printer
 │
LCD Touchscreen
```finish``` 

1.5 Technology Stack
 a. Software
Software implementation involves the installation and configuration of software on the raspberry pi, including the raspberry pi OS CUPS for print management, and RFID authentication software, a web server (such as Apache or Nginx) is used to host a Laravel-based print management application that handles user interaction, document upload, and print queue management. 
 1. Laravel as Backend Framework
 2. PHP as Server-side Programming Language 
 3. MySQL as Relational Database 
 4. RESTful API as Data Communication 
 5. Apache / Nginx as Web Server 
 6. Python + Tkinter as Raspberry Pi Hardware Controller 

 b. Hardware
In the hardware implementation stage, the main components such as Raspberry Pi, RFID RC522, Printer, LCD and Cooling Fan are assembled and configured. This section contains screenshots of the hardware in finished form.

 1. Raspberry Pi 4 Model B | Central server and device controller |
 2. RFID RC522 | User authentication |
 3. Printer | Document printing |
 4. 7" LCD Touchscreen | User interface |
 5. Cooling Fan | Raspberry Pi cooling system |
 6. USB Type-C Power Adapter | Primary power source |
 7. Power Bank | Backup power supply |

 c. Database Structure
The system consists of several main database tables:
| Table | Description |
 1. | users | User accounts and roles |
 2. | admins | Administrator information |
 3. | karyawans | Staff information |
 4. | pelanggans | Student information |
 5. | files | Uploaded document records |
 6. | harga_cetaks | Printing price configuration |
 7. | token_saldo | Balance top-up tokens |
 8. | transaksi | Printing and balance transaction history |

1.6 System Workflow

 1. Users log in using **Email & Password** or an **RFID Card**.
 2. Students upload documents to the system.
 3. Documents can be converted to PDF if required.
 4. Students redeem a balance token to top up their account.
 5. The system calculates the printing cost automatically.
 6. Users authenticate using their RFID card.
 7. Raspberry Pi sends the print command to the connected printer.
 8. The document is printed successfully.
 9. Transaction history is stored in the database.
