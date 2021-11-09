INSERT INTO `prefectures` (`id`, `region_id`, `prefecture_name`) VALUES
(1, 1, '北海道'),
(2, 2, '青森県'),
(3, 2, '岩手県'),
(4, 2, '宮城県'),
(5, 2, '秋田県'),
(6, 2, '山形県'),
(7, 2, '福島県'),
(8, 3, '茨城県'),
(9, 3, '栃木県'),
(10, 3, '群馬県'),
(11, 3, '埼玉県'),
(12, 3, '千葉県'),
(13, 3, '東京都'),
(14, 3, '神奈川県'),
(15, 4, '新潟県'),
(16, 4, '富山県'),
(17, 4, '石川県'),
(18, 4, '福井県'),
(19, 4, '山梨県'),
(20, 4, '長野県'),
(21, 4, '岐阜県'),
(22, 4, '静岡県'),
(23, 4, '愛知県'),
(24, 5, '三重県'),
(25, 5, '滋賀県'),
(26, 5, '京都府'),
(27, 5, '大阪府'),
(28, 5, '兵庫県'),
(29, 5, '奈良県'),
(30, 5, '和歌山県'),
(31, 6, '鳥取県'),
(32, 6, '島根県'),
(33, 6, '岡山県'),
(34, 6, '広島県'),
(35, 6, '山口県'),
(36, 7, '徳島県'),
(37, 7, '香川県'),
(38, 7, '愛媛県'),
(39, 7, '高知県'),
(40, 8, '福岡県'),
(41, 8, '佐賀県'),
(42, 8, '長崎県'),
(43, 8, '熊本県'),
(44, 8, '大分県'),
(45, 8, '宮崎県'),
(46, 8, '鹿児島県'),
(47, 8, '沖縄県');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prefectures`
--
ALTER TABLE `prefectures`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prefectures`
--
ALTER TABLE `prefectures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
