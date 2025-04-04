-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2024 at 04:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_de_gestion_de_ventas`
--

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `sp_add_temporal_code_detail` (IN `productId` INT, IN `quantity` INT, IN `tokenUser` VARCHAR(50))  DETERMINISTIC BEGIN
    DECLARE precioActual DECIMAL(10,2);

    SELECT price INTO precioActual FROM products WHERE id = productId;

    INSERT INTO temporary_details(tokenUser, productId, quantity, sellingPrice, branchId, userCreatedId)
    VALUES (tokenUser, productId, quantity, precioActual, 1, 1);

    SELECT tmp.id, tmp.productId, p.name, tmp.quantity, tmp.sellingPrice
    FROM temporary_details tmp
    INNER JOIN products p ON tmp.productId = p.id
    WHERE tmp.tokenUser = tokenUser;
END$$

CREATE PROCEDURE `sp_add_temporal_name_detail` (IN `productName` VARCHAR(100), IN `quantity` INT, IN `tokenUser` VARCHAR(50))   BEGIN
    DECLARE productId INT;
    DECLARE precioActual DECIMAL(10,2);
    DECLARE no_more_rows BOOLEAN;

    -- Cursor declaration
    DECLARE product_cursor CURSOR FOR 
        SELECT id, price 
        FROM products 
        WHERE LOWER(name) = LOWER(productName);
    
    -- Declare CONTINUE HANDLER to set the no_more_rows flag
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_rows = TRUE;

    -- Open cursor
    OPEN product_cursor;

    read_loop: LOOP
        FETCH product_cursor INTO productId, precioActual;
        IF no_more_rows THEN 
            LEAVE read_loop;
        END IF;

        -- Insert into temporary_details for each fetched product
        INSERT INTO temporary_details(tokenUser, productId, quantity, sellingPrice, branchId, userCreatedId)
        VALUES (tokenUser, productId, quantity, precioActual, 1, 1);
    END LOOP;

    -- Close cursor
    CLOSE product_cursor;

    -- Select the details to return
    SELECT tmp.id, tmp.productId, p.name, tmp.quantity, tmp.sellingPrice
    FROM temporary_details tmp
    INNER JOIN products p ON tmp.productId = p.id
    WHERE tmp.tokenUser = tokenUser;

END$$

CREATE PROCEDURE `sp_delete_temporal_detail` (IN `detailId` INT, IN `token` VARCHAR(50))  DETERMINISTIC BEGIN
    DELETE FROM temporary_details WHERE id = detailId;

    SELECT tmp.id, tmp.productId, p.name, tmp.quantity, tmp.sellingPrice
    FROM temporary_details tmp
    INNER JOIN products p ON tmp.productId = p.id
    WHERE tmp.tokenUser = token;
END$$

CREATE PROCEDURE `sp_process_budget` (IN `userCreatedId` INT, IN `clientId` INT, IN `token` VARCHAR(50), IN `totalWithDiscount` DECIMAL(10,2), IN `branchId` INT, IN `storeId` INT)   BEGIN
    DECLARE supplier INT;
    DECLARE registers INT;
    DECLARE total DECIMAL(10,2);

    -- Contar los registros en temporary_details para el tokenUser
    SELECT COUNT(*) INTO registers FROM temporary_details WHERE tokenUser = token;

    IF registers > 0 THEN
        -- Insertar la cabecera de la estimación, incluyendo storeId
        INSERT INTO estimates (userCreatedId, clientId, totalWithDiscount, branchId, storeId, userUpdatedId)
        VALUES (userCreatedId, clientId, totalWithDiscount, branchId, storeId, NULL);

        SET supplier = LAST_INSERT_ID();

        -- Insertar los detalles de la estimación, incluyendo storeId
        INSERT INTO budget_details(invoiceNumber, productId, quantity, sellingPrice, branchId, storeId, userCreatedId)
        SELECT supplier, productId, quantity, sellingPrice, branchId, storeId, userCreatedId
        FROM temporary_details
        WHERE tokenUser = token;

        -- Calcular el total de la estimación
        SELECT SUM(quantity * sellingPrice) INTO total
        FROM temporary_details
        WHERE tokenUser = token;

        -- Actualizar el total de la estimación
        UPDATE estimates
        SET total = total
        WHERE id = supplier;

        -- Limpiar la tabla temporal temporary_details
        DELETE FROM temporary_details
        WHERE tokenUser = token;

        -- Seleccionar la estimación creada
        SELECT *
        FROM estimates
        WHERE id = supplier;

    ELSE
        SELECT 0;
    END IF;
END$$

CREATE PROCEDURE `sp_process_sale` (IN `userCreatedId` INT, IN `clientId` INT, IN `token` VARCHAR(50), IN `totalWithDiscount` DECIMAL(10,2), IN `branchId` INT, IN `storeId` INT)  DETERMINISTIC BEGIN
    DECLARE invoices INT;
    DECLARE registers INT;
    DECLARE total DECIMAL(10,2);

    -- Contar los registros en temporary_details para el tokenUser
    SELECT COUNT(*) INTO registers FROM temporary_details WHERE tokenUser = token;

    IF registers > 0 THEN
        -- Insertar la cabecera de la factura, incluyendo storeId
        INSERT INTO invoices (userCreatedId, clientId, totalWithDiscount, branchId, storeId, userUpdatedId)
        VALUES (userCreatedId, clientId, totalWithDiscount, branchId, storeId, NULL);

        SET invoices = LAST_INSERT_ID();

        -- Insertar los detalles de la factura, incluyendo storeId
        INSERT INTO invoice_details(invoiceNumber, productId, quantity, sellingPrice, branchId, storeId, userCreatedId)
        SELECT invoices, productId, quantity, sellingPrice, branchId, storeId, userCreatedId
        FROM temporary_details
        WHERE tokenUser = token;

        -- Actualizar el stock de los productos
        UPDATE products p
        INNER JOIN temporary_details dt ON p.id = dt.productId
        SET p.stock = p.stock - dt.quantity
        WHERE dt.tokenUser = token;

        -- Calcular el total de la factura
        SELECT SUM(quantity * sellingPrice) INTO total
        FROM temporary_details
        WHERE tokenUser = token;

        -- Actualizar el total de la factura
        UPDATE invoices
        SET total = total
        WHERE id = invoices;

        -- Limpiar la tabla temporal temporary_details
        DELETE FROM temporary_details
        WHERE tokenUser = token;

        -- Seleccionar la factura creada
        SELECT *
        FROM invoices
        WHERE id = invoices;

    ELSE
        SELECT 0;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--
use ventas;
drop table branches;
CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `cuit` varchar(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phoneNumber` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Argentina',
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `cuit`, `name`, `email`, `phoneNumber`, `address`, `country`, `storeId`, `userCreatedId`, `userUpdatedId`, `createdAt`, `updatedAt`) VALUES
(1, '30123456789', 'Dani Code', 'dani@code.com', 123456789, 'Internet city', 'Argentina', 1, 1, NULL, '2024-06-20 04:58:55', '2024-06-20 04:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `budget_details`
--

CREATE TABLE `budget_details` (
  `id` int(11) NOT NULL,
  `invoiceNumber` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sellingPrice` decimal(10,2) NOT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) DEFAULT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `dni` int(8) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phoneNumber` int(15) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `dni`, `name`, `phoneNumber`, `address`, `email`, `branchId`, `storeId`, `userCreatedId`, `userUpdatedId`, `createdAt`, `updatedAt`) VALUES
(1, 123456789, 'Dani Code', 123456789, 'Internet city', 'primer@cliente.com', 1, 1, 1, NULL, '2024-06-20 05:10:37', '2024-06-20 05:10:37');

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `clientId` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `totalWithDiscount` decimal(10,2) DEFAULT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `clientId` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `totalWithDiscount` decimal(10,2) DEFAULT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` bigint(20) NOT NULL,
  `invoiceNumber` bigint(20) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sellingPrice` decimal(10,2) NOT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `supplier` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `supplier`, `price`, `stock`, `branchId`, `storeId`, `userCreatedId`, `userUpdatedId`, `createdAt`, `updatedAt`) VALUES
(, 'Arepita frita con caraoticas y queso', 1, 3060.30, 9952, 1, 1, 1, 1, '2024-06-20 05:03:02', '2024-06-20 21:22:45');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `cuit_cuil` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tradeName` varchar(100) NOT NULL,
  `phoneNumber` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DESCRIBE `stores`;
-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phoneNumber` int(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phoneNumber`, `address`, `branchId`, `storeId`, `userCreatedId`, `userUpdatedId`, `createdAt`, `updatedAt`) VALUES
(1, 'Arepera Code', 123456789, 'Internet city', 1, 1, 1, NULL, '2024-06-20 05:01:10', '2024-06-20 05:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `temporary_details`
--

CREATE TABLE `temporary_details` (
  `id` int(11) NOT NULL,
  `tokenUser` varchar(50) NOT NULL,
  `productId` int(11) NOT NULL,
  `productName` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sellingPrice` decimal(10,2) NOT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `storeId` int(11) NOT NULL,
  `userCreatedId` int(11) NOT NULL,
  `userUpdatedId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `branchId`, `storeId`, `userCreatedId`, `userUpdatedId`, `createdAt`, `updatedAt`) VALUES
(1, 'Diana', 'admin@gmail.com', '$2y$10$rn/3b1gUlDmMBXLkBgj7PeApfiF4ZEMk26SDX2vpJ3WujxA09F8YW', 1, 1, 1, 1, 1, '2024-06-20 01:19:42', '2024-06-20 01:19:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `budget_details`
--
ALTER TABLE `budget_details`
  MODIFY PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT( 

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `temporary_details`
--
ALTER TABLE `temporary_details`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) AUTO_INCREMENT; 

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_details`
--
ALTER TABLE `budget_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temporary_details`
--
ALTER TABLE `temporary_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
