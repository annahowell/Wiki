SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `wiki`;
CREATE DATABASE `wiki` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `wiki`;



DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `categoryNo` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parentNo` int(11) NOT NULL,
  PRIMARY KEY (`categoryNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `category` (`categoryNo`, `name`, `parentNo`) VALUES
(1,	'Home & Leisure',	0),
(2,	'Computing',	0),
(3,	'Automotive',	0),
(4,	'Arts & Crafts',	0),
(5,	'Gardening',	1),
(6,	'Homeware',	1),
(7,	'Cooking',	1),
(8,	'DIY',	1),
(9,	'Security',	2),
(10,	'Web',	2),
(11,	'Linux',	2),
(12,	'Windows',	2),
(13,	'Mac OS',	2),
(14,	'Applications',	2),
(15,	'Cars',	3),
(16,	'Motorbikes',	3),
(17,	'Vans & Lorries',	3),
(18,	'Repairs',	3),
(19,	'Sewing',	4),
(20,	'Painting',	4),
(21,	'Drawing',	4),
(22,	'Modeling',	4);



DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `postNo` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(2550) COLLATE utf8_unicode_ci NOT NULL,
  `imageName` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'placeholder.png',
  `dateModified` datetime NOT NULL,
  `userNo` int(11) NOT NULL,
  `categoryNo` int(11) NOT NULL,
  PRIMARY KEY (`postNo`),
  KEY `post_fk0` (`userNo`),
  KEY `post_fk1` (`categoryNo`),
  CONSTRAINT `post_fk0` FOREIGN KEY (`userNo`) REFERENCES `user` (`userNo`),
  CONSTRAINT `post_fk1` FOREIGN KEY (`categoryNo`) REFERENCES `category` (`categoryNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `post` (`postNo`, `title`, `content`, `imageName`, `dateModified`, `userNo`, `categoryNo`) VALUES
(1,	'Door Pillar Types',	'Pillars are the vertical or near vertical supports of a car\'s window area or greenhouse-designated respectively as the A, B, C or (in larger cars) D-pillar, moving from the front to rear, in profile view.\r\n\r\nThe consistent **alphabetical** designation of a car\'s pillars provides a common reference for design discussion and critical communication. As an example, rescue teams employ pillar nomenclature to facilitate communication when cutting wrecked vehicles, as when using the jaws of life.\r\n\r\nThe B pillars are sometimes referred to as \"posts\" (two-door or four-door post sedan).',	'4898252312.png',	'2018-04-13 18:01:42',	1,	15),
(2,	'Motorbike Variatons',	'There are many systems for classifying types of motorcycles, describing how the motorcycles are put to use, or the designer\'s intent, or some combination of the two. Six main categories are widely recognized: *cruiser, sport, touring, standard, dual-purpose, and dirt bike*. Sometimes sport touring motorcycles are recognized as a seventh category. Strong lines are sometimes drawn between motorcycles and their smaller cousins, mopeds, scooters, and underbones, but other classification schemes include these as types of motorcycles.\r\n\r\nThere is **no** universal system for classifying all types of motorcycles. There are strict classification systems enforced by competitive motorcycle sport sanctioning bodies, or legal definitions of a motorcycle established by certain legal jurisdictions for motorcycle registration, emissions, road traffic safety rules or motorcyclist licensing. There are also **informal** classifications or nicknames used by manufacturers, riders, and the motorcycling media. Some experts do not recognize sub-types, like naked bike, that \"purport to be classified\" outside the six usual classes, because they fit within one of the main types and are recognizable only by cosmetic changes.',	'5869504771.jpg',	'2018-04-13 18:07:47',	1,	16),
(3,	'Timing and Type',	'If you want succeed with your garden you need to plant the right type of plants, at the right time. You can check both at the United States Department of Agriculture’s plant hardiness zone map. When you’ve confirmed that your plants will grow in your zone, make sure you plant them at the right time of year! Be sure to also check the seed’s information, it will likely be spring or summer.',	'8340829709.jpg',	'2018-04-13 18:34:50',	1,	5),
(4,	'Well Watered',	'Make sure you give young plants **plenty** of water, but always avoid wetting the plant’s leaves! Wet leaves can easily lead to mold, rot, and a sick plant! As your sprouts grow, remember that the general rule of thumb is to give plants an inch of water per week. \r\n\r\nIf you keep the garden close to your house, popping outside to give your plants some water will hardly seem like any effort at all. Be on the look-out for yellow leaves that means too much water!\r\n\r\nSome plants are just known to be easier to grow than others- plant these! For easy-to-grow produce, Hometalk’s gardening experts recommend tomatoes, peppers, onions, chard, basil, and bush beans. Easy to grow and maintain flowers include clematis(a vine), sunflowers, dahlia’s, foxglove, roses, petunia & black eyed susan’s. Just ask your local plant nursery employee to direct you to the “hardy” plants.',	'7051055673.jpg',	'2018-04-13 18:36:31',	1,	5),
(5,	'Fire Retardant Paint',	'Some of you might not know that such a thing as fire retardant paint exists. There are different types of paint with different levels and methods of protection. They are not normally used in domestic settings however maybe they should be. If you are panting a public space or a business one you should probably consider using a fire retardant paint – certainly in key escape routes. We’ll be providing a summary below to better explain the fundamental differences and the benefits of these paints.\r\nBritish Safety Standards\r\n\r\nThe British BS 476 safety standard is a regulation that sets out the rules to follow as it relates to building structures and the materials used within them. There are different classifications for the regulations which relate to paint.\r\n\r\nThe Class 1 is a paint coating that ensures zero spread of a fire. With Class 0, the surface areas are expected to have a limited ability to catch fire; used in high-risk places like access corridors and escape routes out of the building. There is another classification which is known as “fire resistance” which ensures that fire won’t spread for 30 minutes in the hope it will be enough time to evacuate everyone from the building.\r\n\r\n### Paint Types\r\nThere are two main paint types that resist fire propagation to a lesser or greater extent. There are fire retardant paints and fire-resistant ones otherwise known as intumescent paint. There are also separate sprays and varnishes that may be applied over paint to provide additional benefits.\r\n\r\n### Fire Retardant Paints\r\nThe fire-retardant paints are the strongest ones. The product is intended to prevent fire from spreading and in many cases, to put the flame out altogether. The fire suppression technology is achieved by a gas release that happens when the paint heats up. These paints must pass BS 476, part 7, one of the British safety standards. Public areas in restrooms, hotels and other common areas often use this type of paint.\r\n\r\n### Intumescent Paints\r\nIntumescent paint doesn’t function the same way. Instead of releasing a gas, when the heat reaches a set level, the paint will burn and create a bubble over the exposed area to protect it. The idea is that a nearby wall that’s close to the fire but not actually on fire yet reacts to the growing heat level nearby and forms a protective shell over the walls. By doing so, the walls are protected and far less likely to catch fire too.',	'8101494657.jpg',	'2018-04-13 19:38:02',	1,	8),
(6,	'Hone Those Knife Skills',	'Always keep your fingers tucked in on your non-dominant hand, using your upper knuckles to guide your knife as you slice. If your finger tips are tucked in, you cant ever cut them! And for the highest degree of control over your knife. hold the knife by the blade, pinching the bottom of the blade between your thumb and the side of your forefinger. Grip your middle, ring, and pinky fingers around the handle for support, and avoid laying your forefinger over the spine of the knife.',	'9419294126.jpg',	'2018-04-13 18:41:40',	1,	7),
(7,	'Turn up the Heat!',	'Don’t be scared of the heat! If you are looking for a sear, you need to bring on high levels of heat. Without a smoking-hot pan, it will be close to impossible to achieve a crispy, caramelized browning on your steak, pork, chicken or fish. Also be sure not to overcrowd a pan when searing, otherwise the pan will cool down too much, and there will not be enough heat from the bottom of the pan or circulating around the meat in the pan.',	'1130031972.png',	'2018-04-13 18:43:49',	1,	7),
(8,	'Poach a Perfect Egg',	'For the perfect poached egg, use a wide pot with enough water to have the egg floating an inch above the bottom of the pot, filling 2/3 with water and 1/3 with white vinegar. Water should be at a slow boil, with tiny bubbles coming from the bottom of the pot. Crack the egg in a ramekin first and drop the egg slowly in the water. If the egg is fresh, the egg white will enrobe the yolk nicely and make the perfect poached egg. Cook to your liking, soft yolk or firm.',	'8574186617.jpg',	'2018-04-13 18:45:25',	1,	7),
(9,	'Should Sofas be placed against Walls?',	'It\'s a common question when it comes to arranging furniture: Should your sofa be against the wall in your living room?\r\n\r\nWhile it\'s a popular layout option – especially if you\'re restricted on space – there are designers who say a sofa against a wall is a decorating mistake that should be avoided. So what exactly is the answer? Julia Kendell, interior designer and TV presenter (known for ITV\'s 60 Minute Makeover and BBC One\'s DIY SOS), reveals how a sofa should be positioned.\r\n\r\n\'If you\'ve got a small room, you probably don\'t have a lot of choice,\' Julia tells House Beautiful. \'But if you have a large space, particularly for those with open plan spaces, you can pull your sofas closer into the room rather than up against the wall, as it can create a more intimate environment.\'\r\n\r\nJulia says the debate isn\'t actually about whether a sofa is against the wall or not: \'It\'s really about making sure the space that you\'re creating within two or three sofas gives you the right distance for chatting.',	'7533790978.jpg',	'2018-04-13 18:48:47',	1,	6),
(10,	'Leasing a Van for your Business',	'In the last two years, new start-up business registrations have increased with 2014 seeing 581,000 new companies in the UK.\r\n\r\nRegistrations continue to grow as people take their chance to chase their dreams, work for themselves or work when they are able. While these are all valid reasons to consider starting a business, once you’ve created everything the hard work really starts.\r\n\r\nFor a lot of businesses, a van is a vital asset. It’s a tool that facilitates your trade, rather than a means of commuting to and from work. Function over fashion, as it were. The costs associated with starting a business could very well leave you struggling to afford a new van. Enter van leasing.\r\n\r\n### Van leasing could help your business\r\nBy leasing you’ll get a brand new van at a much lower cost than if you were to purchase the van outright. Van leasing is all about flexibility, freedom to fix your monthly costs and the ability to change your van every few years.\r\n\r\nLeasing requires an initial payment at the start of the agreement, followed by regular monthly payments for the duration of your lease. Lease terms can last from two to five years and at the end of the term the vehicle must be sold to cover a ‘balloon payment’. The balloon payment is calculated using the age of the van at the end of the agreement, along with its projected mileage. It’s not unknown for businesses to make a profit on the sale.\r\n\r\nDepending on how your business is set-up, there are potential tax benefits associated with leasing a van, rather than buying one – especially when the van is a working vehicle.\r\n\r\n### What you need to know before leasing a van\r\nIt’s not as simple as heading online or making a phone call. As a start-up, getting approved for finance can prove difficult. This is because a finance company needs to assess the risk of you defaulting on your commitments. A new business is not going to have a long trading history, which means a finance company has no evidence of how the business keeps up with financial obligations.',	'5387896424.png',	'2018-04-13 18:53:00',	1,	17),
(11,	'How to add a table to Notes App',	'Here\'s how to add a table to a Note:\r\n\r\n1. Click on the table icon in the menu bar.\r\n2. To add more rows you can either click in the last cell and click on the Tab key, or you can click on one of the small boxes with three dots inside that appear beside the rows and columns when they are selected.\r\n3. Click on that box to see a drop-down menu with the option Add Row Above, Add Row Below, and Delete Row, or Add Column Before, Add Column After, and Delete Column, depending on what area of the table you are extending.\r\n\r\nAs you add more columns each column will get smaller, once the table is wider than a single page a bar will appear at the bottom to make swiping across to view more data simpler.',	'4263245068.png',	'2018-04-13 20:19:50',	1,	13),
(12,	' Sync Desktops on multiple Macs ',	'If you\'re anything like us, your Mac filing system involves everything being saved to the Desktop. One of the best new features that came to MacOS in Sierra is the way that the Desktop now syncs across iCloud - so you can go to your iCloud Drive on any of your devices and access the files and folders on your desktop. This is great if you have more than one Mac because you can essentially merge both desktops together.\r\n\r\nIt\'s not just your Desktop that automatically syncs across iCloud Drive, you can also access your Documents folder there too. The only limitation is how much space you have available on iCloud Drive, if you are paying for more than the standard 5GB this could be a useful feature. Luckily prices of iCloud storage have fallen recently: 50GB now costs 79p per month.\r\n\r\nTo start sharing your Desktop in iCloud go to System Preferences > iCloud. Make sure that iCloud Drive is selected at the top and click on Options. Select Desktop & Documents to share those files.\r\n\r\niCloud will then upload your files to the cloud.\r\n\r\nOnce you have done this you will be able to access files on your desktop at home anywhere you can log on to the internet. Just go to iCloud.com and log on and then open the Desktop folder there.',	'2123055303.png',	'2018-04-13 20:22:03',	1,	13),
(13,	'Launch task programs with your keyboard',	'Every program to the right of the Start button is assigned its own numerical shortcut, with the first program being \"1,\" the second being \"2,\" and so on, all the way to the 10th taskbar shortcut, which gets \"0.\" Pressing the Windows key, plus the number of the program you want to open, launches it. For example, in the image at left, pressing **Win + 3** launches the Chrome browser.',	'2974479979.png',	'2018-04-13 20:24:23',	1,	12),
(14,	'About Google Chrome',	'Browsers have evolved far beyond their original mission of providing one-way windows into the internet. Indeed, as more services migrate to the cloud, browsers only reinforce their role as multi-function boxes of digital magic.\r\n\r\nAll the important internet things are available in browser form - from communication tools to **productivity** suites to disposable escapist entertainment. It\'s almost like the browser has become an OS in and of itself. In fact, you could say that\'s exactly where things are headed.\r\n\r\nWhile there is plenty of debate out there as to which browser is best, for my money it\'s the sleek, minimalist package known as Google Chrome. And the data shows that most users agree: According to the latest numbers from W3Schools, 76.3 percent of people are using Chrome, more than double the next highest, Firefox (13.3 percent), with IE/Edge (4.6 percent) and Safari (3.3 percent) far behind.\r\n\r\nThese numbers are based on visitors to W3\'s site, so they aren\'t a **definitive** representation of the greater population; Net Applications gave Chrome about 59.49 percent of the global browser market share in June, for example. But no matter what the final tally may be, Chrome is undeniably a popular browser, and if you\'re not using it, you should at least consider checking it out.\r\n\r\nOne of the reasons for Chrome\'s popularity is its clean, **polished** UI and its versatility. While Chrome\'s abilities multiply greatly when you consider the near-bottomless library of extensions, there\'s a bounty of stock functionality embedded all throughout Chrome\'s guts that you may not even know about.',	'3095320405.jpg',	'2018-04-13 20:26:51',	1,	10),
(15,	'VLC Media Player',	'VLC is most commonly known for being a media player, although it does much more. When installed, it downloads codecs for virtually every kind of audio or video file, meaning you\'re unlikely to ever have playback issues again. The software can also play DVDs. \r\n\r\nYou can use VLC to clip video files and even convert them from one format to another – from AVI to MP4, for example. See our guide on this here. The media player client can also act as a server, allowing you to stream media from one device to another.',	'3987048804.jpg',	'2018-04-13 20:28:39',	1,	11),
(16,	'Flux',	'Ever notice how people texting at night have that eerie blue glow?\r\n\r\nOr wake up ready to write down the Next Great Idea, and get blinded by your computer screen?\r\n\r\nDuring the day, computer screens look good, they\'re designed to look like the sun. But, at 9PM, 10PM, or 3AM, you probably shouldn\'t be looking at the sun.\r\nf.lux\r\n\r\nf.lux fixes this: it makes the color of your computer\'s display adapt to the time of day, warm at night and like sunlight during the day.\r\n\r\nIt\'s even possible that you\'re staying up too late because of your computer. You could use f.lux because it makes you sleep better, or you could just use it just because it makes your computer look better. \r\n\r\nGet flux [here](https://justgetflux.com/)',	'9694481392.png',	'2018-04-13 20:30:25',	1,	14),
(17,	'What is drawing?',	'The term drawing is applied to works that vary greatly in technique. It has been understood in different ways at different times and is difficult to define. During the Renaissance the term \'disegno\' implied drawing both as a technique to be distinguished from colouring and also as the creative idea made visible in the preliminary sketch.\r\n\r\nThe Shorter Oxford Dictionary defines drawing as:\r\n\r\n    \'the formation of a line by drawing some tracing instrument from point to point of a surface; representation by lines; delineation as distinguished from painting...the arrangement of lines which determine form.\'\r\n\r\nDespite this insistence on the formation of line and the implied lack of colour, few would deny that a work formed by dots or shading or wholly in line but in a range of colours is a drawing.\r\n\r\nThe following drawings, made in different ways, have been selected to help define and also to stretch the boundaries of what drawing is. They vary in the medium used, which includes metal-point, graphite, charcoal, ink, and chalk. Some fulfill the strict dictionary definition of drawing, others do not.',	'4580040504.jpg',	'2018-04-13 20:33:30',	1,	21);



DROP TABLE IF EXISTS `rating`;
CREATE TABLE `rating` (
  `userNo` int(11) NOT NULL,
  `postNo` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`userNo`,`postNo`),
  KEY `rating_fk1` (`postNo`),
  CONSTRAINT `rating_fk0` FOREIGN KEY (`userNo`) REFERENCES `user` (`userNo`),
  CONSTRAINT `rating_fk1` FOREIGN KEY (`postNo`) REFERENCES `post` (`postNo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `rating` (`userNo`, `postNo`, `rating`) VALUES
(1,	1,	4),
(1,	2,	1),
(1,	13,	4),
(1,	14,	1),
(1,	16,	4),
(1,	17,	4),
(2,	1,	3),
(2,	2,	4),
(2,	3,	4),
(2,	5,	2),
(2,	6,	3),
(2,	7,	2),
(2,	8,	5),
(2,	9,	2),
(2,	12,	4),
(2,	13,	5),
(2,	14,	1),
(2,	15,	5),
(2,	16,	3),
(2,	17,	5);



DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userNo` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `displayname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- All passwords below are identical and are: Password123
INSERT INTO `user` (`userNo`, `username`, `password`, `displayname`, `email`) VALUES
(1,	'anna',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	'anna display name',	'anna@lol.com');
(2,	'example',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	'Example rater',	'example@lol.com');
-- All passwords above are identical and are: Password123
