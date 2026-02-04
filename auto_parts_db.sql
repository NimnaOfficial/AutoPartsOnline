-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 04, 2026 at 02:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auto_parts_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(4, 1, 22, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `submitted_at`, `created_at`) VALUES
(1, 'nimna', 'nimna@gmail.com', 'no', 'note', '2026-02-03 23:56:06', '2026-02-03 23:56:06'),
(2, 'vv', 'vv@gmail.com', 'ee', 'ee', '2026-02-03 23:58:28', '2026-02-03 23:58:28'),
(3, 'nimna', 'nima@gmail.com', 'empty', 'emp', '2026-02-03 23:59:12', '2026-02-03 23:59:12');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `order_date`) VALUES
(1, 1, 24, 2, 50000.00, 'Shipped', '2026-02-03 23:38:01'),
(2, 1, 23, 2, 9000.00, 'Paid', '2026-02-03 23:38:01'),
(3, 2, 23, 1, 4500.00, 'Paid', '2026-02-04 00:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `stock_quantity`, `created_at`) VALUES
(1, 'Twin-Scroll Turbocharger', 'Boost your engine power with this high-performance twin-scroll turbo.', 125000.00, 'https://turbobits.co.nz/cdn/shop/files/HX35W_-_4955157___8-Photoroom__1__TKQX9B60CHRT.jpg?v=1757290195', 5, '2026-02-03 22:31:58'),
(2, 'Brembo Brake Kit', 'Red ceramic brake calipers and drilled rotors for maximum stopping power.', 85000.00, 'https://tomsracing.com.au/cdn/shop/files/TomsBrembo.jpg?v=1711033920', 8, '2026-02-03 22:31:58'),
(3, 'Titanium Exhaust System', 'Lightweight titanium exhaust for a deep, aggressive roar.', 65000.00, 'https://fdracing.co.uk/cdn/shop/products/Ti_Audi_RS3_Exhaust-1.jpg?v=1741086030', 4, '2026-02-03 22:31:58'),
(4, 'Cold Air Intake', 'Increase airflow and horsepower with this high-flow intake system.', 25000.00, 'https://www.autozone.com/cdn/images/Blog/2019/12/77-2617kc_ENG-1.jpg', 15, '2026-02-03 22:31:58'),
(5, 'Adjustable Coilovers', 'Lower your ride height and improve handling with adjustable suspension.', 55000.00, 'https://coiloverdepot.com/cdn/shop/products/XLIT191MA-Lexus-IS350-RSR-coilovers-1_5000x.jpg?v=1581948988', 6, '2026-02-03 22:31:58'),
(6, 'Intercooler Kit', 'Keep your turbo cool with this front-mount intercooler.', 45000.00, 'https://performancedieselintercoolers.com.au/wp-content/uploads/2025/02/ToyotaHDJ78_HDJ79_HZJ79SeriesLandcruiserFrontMountIntercoolerKitstdintake.jpg', 7, '2026-02-03 22:31:58'),
(7, 'Matte Black Alloy Rim 19\"', 'Sleek 19-inch matte black alloy wheel. Price per rim.', 42000.00, 'https://www.kipardoracing.com/wp-content/uploads/2025/08/KF21-black-alloy-wheels-for-white-cars.jpg', 20, '2026-02-03 22:31:58'),
(8, 'Chrome Spoke Wheel 20\"', 'Classic chrome finish for that luxury look.', 48000.00, 'https://thumbs.dreamstime.com/b/close-up-classic-car-wheel-showcasing-intricate-chrome-details-wire-spoke-design-bright-daylight-detailed-vintage-410935485.jpg', 12, '2026-02-03 22:31:58'),
(9, 'Racing Slick Tires', 'High-grip track tires for race day. Not for highway use.', 35000.00, 'https://formulapedia.com/wp-content/uploads/2023/02/slick-tires-F1.jpg', 10, '2026-02-03 22:31:58'),
(10, 'All-Terrain Offroad Tires', 'Rugged tires built for mud, sand, and rocky terrains.', 38000.00, 'https://rbptires.com/wp-content/uploads/How-To-Choose-Off-Road-Tires-Image-1.jpg', 16, '2026-02-03 22:31:58'),
(11, 'Tire Inflator Kit', 'Portable digital tire inflator with auto-stop function.', 8500.00, 'https://jacosuperiorproducts.com/cdn/shop/files/TrailPro-newmain2_large.png?v=1718199939', 30, '2026-02-03 22:31:58'),
(12, 'Recaro Racing Seat', 'Ergonomic bucket seat with 5-point harness support.', 75000.00, 'https://i.pinimg.com/originals/78/e7/0b/78e70ba07f39ff92cd064638437f61f8.jpg', 4, '2026-02-03 22:31:58'),
(13, 'Carbon Fiber Steering Wheel', 'Real carbon fiber wheel with leather grips.', 32000.00, 'https://carbonedgedynamics.com/cdn/shop/files/BB3FB2B6-1850-4265-84A3-BB561DD03D6D.jpg?v=1705627554', 10, '2026-02-03 22:31:58'),
(14, 'Pioneer Subwoofer 12\"', 'Deep bass subwoofer box for ultimate sound experience.', 28000.00, 'https://pioneer-mea.com/wp-content/themes/pioneer_ME/images/banner_middle_shallow%20series.jpg', 15, '2026-02-03 22:31:58'),
(15, 'RGB Footwell Lights', 'App-controlled LED lighting strip for car interior.', 4500.00, 'https://i.ebayimg.com/images/g/UL0AAOSw7Ixl~uNU/s-l1200.jpg', 50, '2026-02-03 22:31:58'),
(16, 'Leather Seat Covers', 'Premium PU leather seat covers, universal fit.', 12500.00, 'https://delicate-leather.com/cdn/shop/files/Red_2.jpg?v=1701234535&width=1200', 25, '2026-02-03 22:31:58'),
(17, 'Carbon Fiber Spoiler', 'Aerodynamic GT wing for downforce and style.', 45000.00, 'https://www.maperformance.com/cdn/shop/products/nrg-nrg-carb-a590nrg-11955330842694.jpg?v=1630755754&width=780', 5, '2026-02-03 22:31:58'),
(18, 'LED Headlight Conversion Kit', 'Bright white 6000K LED bulbs for better night vision.', 12000.00, 'https://cdn.shopify.com/s/files/1/1509/2734/products/CLDHE9004_B_1aee14e6-1ee0-45c2-b3b9-1ff7e8f769a1.jpg?v=1606159707', 40, '2026-02-03 22:31:58'),
(19, 'Roof Rack Carrier', 'Heavy-duty roof rack for luggage and outdoor gear.', 22000.00, 'https://www.picclickimg.com/3GcAAOSw3RlgO8Zv/Halfords-Exodus-470L-60KG-Roof-Box-Roof-Bars.webp', 10, '2026-02-03 22:31:58'),
(20, 'Front Bumper Lip', 'Sporty front splitter to protect bumper and add style.', 15000.00, 'https://emeraldstruts.com/cdn/shop/products/814-dsc03500-kopiowanie-1280x720_1245x700.jpg?v=1704563087', 12, '2026-02-03 22:31:58'),
(21, 'Full Synthetic Motor Oil 4L', 'High-mileage advanced formula 5W-30 oil.', 8500.00, 'https://media.istockphoto.com/id/1326614900/photo/after-changing-the-oil-pour-in-the-fresh-engine-oil.jpg?s=612x612&w=0&k=20&c=9ZH9Y0d5M9-oP6R7D0ThaExHYzrlpbFL1qxVw5UVR6w=', 100, '2026-02-03 22:31:58'),
(22, 'Professional Tool Kit', '108-piece mechanics tool set with socket wrench.', 18500.00, 'https://images.unsplash.com/photo-1530124566582-a618bc2615dc?auto=format&fit=crop&w=500&q=60', 20, '2026-02-03 22:31:58'),
(23, 'Ceramic Coating Spray', 'Hydrophobic shine and protection for car paint.', 4500.00, 'https://careaze.co/cdn/shop/files/DSC09816_1080_edited_d998f830-1e32-4ff6-b73d-e235823f9ec1.jpg?v=1763068889', 42, '2026-02-03 22:31:58'),
(24, 'Heavy Duty Car Battery', 'Maintenance-free 12V battery with 2-year warranty.', 25000.00, 'https://advancedbatterysupplies.co.uk/wp-content/uploads/2015/02/249-HD-car-battery.jpg', 13, '2026-02-03 22:31:58'),
(28, 'NOS', 'A nitrous oxide engine, or nitrous oxide system (NOS)', 44000.00, 'https://www.slashgear.com/img/gallery/how-does-a-nitrous-system-work-and-what-does-it-do-to-your-engine/l-intro-1734026212.jpg', 10, '2026-02-04 00:57:30'),
(29, 'Fog Light', ' LED Fog Light Bulb Lamp For Cars and Motorcycles Dual Color', 7000.00, 'https://www.a10moto.com/cdn/shop/files/K127093S13-1.25_-Crash-Bar-Mounts-w--S2-Sport-Amber-Driving-Combo-and-Plug_b273716d-d46f-4b01-83e9-e70d2bc411ac_1024x.jpg?v=1723750315', 45, '2026-02-04 01:01:12'),
(30, 'Dark Green UV Cut Glass', ' Dark Green UV Cut Glass is a new windshield and window glass that blocks over 80% of the sun\'s UV rays and reduces solar heat penetration.', 28000.00, 'https://m.media-amazon.com/images/I/61+UwhIv30L._AC_UF894,1000_QL80_.jpg', 21, '2026-02-04 01:08:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `address`, `username`, `password`, `role`) VALUES
(1, 'nimna', 'nimna@gmail.com', '0711111111', 'ddfd', 'www', '$2y$10$l0ff27QoZY7SCc3SYDQRLeL85IcRo2w94bJ1VLE6n6qlfV58Urmp.', 'customer'),
(2, 'nimnas', 'nimn@gmail.com', '0711111122', 'Colombo', 'me', '$2y$10$Fy1SmWv4DBLGNwoOeWxDJ.Gdha7XkQZtb0dZ20KRe2aaaZf294JUG', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `user_cars`
--

CREATE TABLE `user_cars` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `plate_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_cars`
--
ALTER TABLE `user_cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_cars`
--
ALTER TABLE `user_cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `user_cars`
--
ALTER TABLE `user_cars`
  ADD CONSTRAINT `user_cars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
