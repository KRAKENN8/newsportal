<?php
/**
 * CyberPulse Database Seeder (English)
 * Populates English categories, users, articles with tech illustrations, and comments.
 */
require_once __DIR__ . '/inc/Database.php';

$db = new Database();

echo "Starting CyberPulse Database Migration & English Seeding...\n";

// 1. Upgrade schema
echo "1. Upgrading table schema...\n";
$db->executeRun("ALTER TABLE news MODIFY picture MEDIUMBLOB NOT NULL");
$db->executeRun("ALTER TABLE category CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$db->executeRun("ALTER TABLE news CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$db->executeRun("ALTER TABLE comments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$db->executeRun("ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// 2. Clear old test data
echo "2. Cleaning old data...\n";
$db->executeRun("SET FOREIGN_KEY_CHECKS = 0");
$db->executeRun("TRUNCATE TABLE comments");
$db->executeRun("TRUNCATE TABLE news");
$db->executeRun("TRUNCATE TABLE category");
$db->executeRun("SET FOREIGN_KEY_CHECKS = 1");

// 3. Ensure admin and user exist
echo "3. Seeding users...\n";
$adminExists = $db->getOne("SELECT id FROM users WHERE email='admin@newsportal.ee'");
$adminPassHash = password_hash('123456', PASSWORD_DEFAULT);
$today = date('Y-m-d');

if ($adminExists) {
    $db->executeRun("UPDATE users SET username='CyberAdmin', password='$adminPassHash', status='admin', pass='123456' WHERE email='admin@newsportal.ee'");
    $adminId = $adminExists['id'];
} else {
    $db->executeRun("INSERT INTO users (username, email, password, status, registration_date, pass) VALUES ('CyberAdmin', 'admin@newsportal.ee', '$adminPassHash', 'admin', '$today', '123456')");
    $u = $db->getOne("SELECT id FROM users WHERE email='admin@newsportal.ee'");
    $adminId = $u['id'];
}

$userExists = $db->getOne("SELECT id FROM users WHERE email='user@newsportal.ee'");
if ($userExists) {
    $db->executeRun("UPDATE users SET username='NeoReader', password='$adminPassHash', status='user', pass='123456' WHERE email='user@newsportal.ee'");
    $userId = $userExists['id'];
} else {
    $db->executeRun("INSERT INTO users (username, email, password, status, registration_date, pass) VALUES ('NeoReader', 'user@newsportal.ee', '$adminPassHash', 'user', '$today', '123456')");
    $u = $db->getOne("SELECT id FROM users WHERE email='user@newsportal.ee'");
    $userId = $u['id'];
}

// 4. Insert Categories (English)
echo "4. Seeding English categories...\n";
$categories = [
    1 => 'AI & Neural Networks',
    2 => 'Hardware & Gadgets',
    3 => 'Cybersecurity',
    4 => 'Gaming & Esports',
    5 => 'Space & Science'
];

foreach ($categories as $id => $name) {
    $db->executeRun("INSERT INTO category (id, name) VALUES ($id, '$name')");
}

// 5. SVG Image generator helper
function createTechSvg($title, $catColor, $iconSymbol, $subtitle) {
    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0b0f19" />
      <stop offset="50%" stop-color="#111827" />
      <stop offset="100%" stop-color="#1a2333" />
    </linearGradient>
    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
      <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255, 255, 255, 0.04)" stroke-width="1"/>
    </pattern>
    <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="8" result="blur" />
      <feComposite in="SourceGraphic" in2="blur" operator="over" />
    </filter>
  </defs>

  <!-- Background -->
  <rect width="800" height="450" fill="url(#bgGrad)" />
  <rect width="800" height="450" fill="url(#grid)" />

  <!-- Ambient Glow Circles -->
  <circle cx="650" cy="120" r="180" fill="$catColor" opacity="0.15" filter="url(#glow)" />
  <circle cx="150" cy="350" r="140" fill="#a855f7" opacity="0.12" filter="url(#glow)" />

  <!-- Decorative Circuit lines -->
  <path d="M 50 100 L 200 100 L 250 150 L 550 150 L 600 100 L 750 100" fill="none" stroke="$catColor" stroke-width="2" opacity="0.3"/>
  <circle cx="250" cy="150" r="4" fill="$catColor" opacity="0.6"/>
  <circle cx="550" cy="150" r="4" fill="$catColor" opacity="0.6"/>
  <path d="M 100 380 L 300 380 L 350 330 L 700 330" fill="none" stroke="#a855f7" stroke-width="1.5" opacity="0.25"/>

  <!-- Center Card Area -->
  <rect x="70" y="70" width="660" height="310" rx="16" fill="rgba(17, 24, 39, 0.6)" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>

  <!-- Top Badge -->
  <rect x="110" y="105" width="200" height="34" rx="17" fill="rgba(255, 255, 255, 0.05)" stroke="$catColor" stroke-width="1.5"/>
  <text x="210" y="128" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="13" font-weight="700" fill="$catColor" text-anchor="middle" letter-spacing="1.5">CYBERPULSE // LAB</text>

  <!-- Big Icon/Symbol -->
  <text x="640" y="240" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="90" text-anchor="middle" opacity="0.85">$iconSymbol</text>

  <!-- Title Text -->
  <text x="110" y="195" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="24" font-weight="800" fill="#ffffff" width="460">
    $title
  </text>

  <!-- Subtitle -->
  <text x="110" y="245" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="15" fill="#9ca3af" font-weight="400">
    $subtitle
  </text>

  <!-- Footer Tagline -->
  <line x1="110" y1="310" x2="690" y2="310" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
  <text x="110" y="345" font-family="monospace" font-size="13" fill="#6b7280">SYS_STATUS: ONLINE // VERIFIED ARTICLE</text>
  <text x="690" y="345" font-family="monospace" font-size="13" fill="$catColor" text-anchor="end">SEC_LEVEL: 01</text>
</svg>
SVG;
}

// 6. News Articles Data (English)
echo "5. Seeding English news articles...\n";
$articles = [
    [
        'title' => 'Quantum Computing Leap: Physicists Achieve 1,000-Qubit Room-Temperature Processor',
        'category_id' => 5,
        'catColor' => '#06b6d4',
        'icon' => '⚛️',
        'subtitle' => 'Quantum advantage reaches a landmark stability milestone',
        'text' => "An international consortium of quantum physicists and computer scientists has announced a landmark breakthrough in quantum information architecture. Researchers successfully demonstrated a 1,024-qubit quantum processor operating with sustained coherence near ambient temperature using diamond nitrogen-vacancy (NV) centers and photonic interconnects.\n\n" .
                  "The new quantum error-correction protocol reduced hardware noise by 94%, paving the way for molecular structure modeling in drug discovery, room-temperature superconductor synthesis, and cryptographic resilience analysis.\n\n" .
                  "«We have crossed the threshold from cryogenic laboratory experiments to practical quantum advantage,» stated the project lead at the Global Quantum Physics Symposium in Geneva. Commercial deployments for supercomputing clusters are slated for next quarter."
    ],
    [
        'title' => 'Multimodal AI Agents: Autonomous Systems Architect Software from Scratch',
        'category_id' => 1,
        'catColor' => '#8b5cf6',
        'icon' => '🧠',
        'subtitle' => 'Reasoning-first language models solve complex engineering tasks',
        'text' => "Leading artificial intelligence research laboratories have unveiled the next generation of reasoning-first multimodal models (System 2 Thinking). The new autonomous agents move beyond snippet generation to designing microservice architectures, writing test suites, provisioning databases, and self-healing regression bugs in real time.\n\n" .
                  "On the industry-standard SWE-bench benchmark, the autonomous agent successfully resolved 88.6% of real-world GitHub issues without human intervention, establishing a historic world record.\n\n" .
                  "Engineers note that the discipline is evolving rapidly: developers are shifting focus from repetitive boilerplate syntax toward high-level conceptual modeling, system design, and safety boundaries."
    ],
    [
        'title' => '2-Nanometer Silicon Unveiled: Next-Gen Processors Feature 120 TOPS NPUs',
        'category_id' => 2,
        'catColor' => '#10b981',
        'icon' => '⚡',
        'subtitle' => 'New silicon geometry transforms mobile and edge computation',
        'text' => "At the annual semiconductor technology summit, leading chipmakers announced the commercial tape-out of 2nm GAAFET (Gate-All-Around) processors designed for next-generation mobile and edge computing devices.\n\n" .
                  "The new architecture delivers a 35% gain in energy efficiency while packing a dedicated Neural Processing Unit (NPU) capable of 120 TOPS. This enables local execution of heavy multimodal language models with billions of parameters directly on smartphones without transmitting data to remote cloud servers.\n\n" .
                  "The first flagship consumer devices powered by the new silicon platform are scheduled to arrive on retail shelves this autumn."
    ],
    [
        'title' => 'Global Cybersecurity Operation Dismantles 3-Million-Device IoT Botnet',
        'category_id' => 3,
        'catColor' => '#ef4444',
        'icon' => '🛡️',
        'subtitle' => 'Joint operation by Interpol and elite cybersecurity strike teams',
        'text' => "Cybersecurity agencies and international law enforcement have neutralized the command-and-control infrastructure of one of history's largest botnets.\n\n" .
                  "The botnet compromised IoT hardware, smart home gateways, and edge routers to launch massive volumetric DDoS attacks peaking at 4.8 Terabits per second against financial clearinghouses and energy grids. The coordinated sweep disabled server nodes across 12 countries and seized decryption keys that will allow thousands of affected organizations to recover encrypted systems.\n\n" .
                  "Security teams strongly urge network administrators to update border gateway router firmware and disable unauthenticated remote management ports."
    ],
    [
        'title' => 'Photoreal Engine 6: Real-Time Path Tracing and Neural Physics Simulation',
        'category_id' => 4,
        'catColor' => '#f59e0b',
        'icon' => '🎮',
        'subtitle' => 'Esports arenas and cinematic gameplay graphics of the future',
        'text' => "Game developers and graphics engineers have pulled back the curtain on Photoreal Engine 6, obliterating the distinction between pre-rendered CGI cinema and real-time interactive gameplay.\n\n" .
                  "Key innovations include dynamic full path tracing running at 120 frames per second on mainstream GPUs, neural physics simulations that calculate stress fractures procedurally, and streaming support for seamless open worlds spanning thousands of square kilometers without loading screens.\n\n" .
                  "Top esports titles and AAA studios have already pledged adoption of the engine, with interactive developer preview kits available for immediate download."
    ],
    [
        'title' => 'Europa Mission: Deep-Space Probe Transmits Radar Sonar of Subsurface Ocean',
        'category_id' => 5,
        'catColor' => '#3b82f6',
        'icon' => '🚀',
        'subtitle' => 'Spacecraft spectrographs detect complex organic compounds',
        'text' => "An autonomous deep-space exploration probe has successfully completed its gravitational slingshot maneuver and established a stable orbit around Jupiter's moon, Europa. High-frequency ice-penetrating radar confirmed the existence of a global liquid water ocean extending over 100 kilometers beneath the crust.\n\n" .
                  "Spectrometric analysis of water vapor plumes erupting through ice fissures revealed significant concentrations of carbon, methane, and complex prebiotic organic molecules. Scientists are now preparing Phase 2: a thermal-drill lander engineered to melt through the ice shell and sample the subsurface ocean for potential microbial signatures."
    ]
];

$conn = $db->connect();
$stmt = $conn->prepare("INSERT INTO news (title, text, picture, category_id, user_id) VALUES (:title, :text, :picture, :category_id, :user_id)");

foreach ($articles as $art) {
    $svg = createTechSvg($art['title'], $art['catColor'], $art['icon'], $art['subtitle']);
    $stmt->execute([
        ':title' => $art['title'],
        ':text' => $art['text'],
        ':picture' => $svg,
        ':category_id' => $art['category_id'],
        ':user_id' => $adminId
    ]);
}

// 7. Seed Comments (English)
echo "6. Seeding English comments...\n";
$comments = [
    [1, $userId, 'Incredible leap in quantum coherence! Operating near ambient temperature solves the massive cryogenic cooling bottleneck.'],
    [1, $adminId, 'Editorial note: We will continue updating this report as more benchmark data is released by the laboratory.'],
    [2, $userId, 'An 88.6% solve rate on SWE-bench is astounding. Software engineering is officially evolving toward high-level systems architecture.'],
    [2, $adminId, 'Agreed. Designing clean API contracts, system boundaries, and verifiable specifications will be the defining skills of the decade.'],
    [3, $userId, '120 TOPS locally on mobile silicon means zero-latency voice assistants with full privacy. Can\'t wait for the autumn devices.'],
    [4, $userId, 'Major credit to the international infosec teams. Securing default router credentials should be enforced by industry regulations.'],
    [5, $userId, 'The dynamic lighting and neural reflections in Photoreal 6 look unreal. The real-time destruction demo is breathtaking.'],
    [6, $userId, 'Europa remains our best bet for finding life in the solar system. The prebiotic chemical signatures are extraordinarily promising!']
];

$stmtComment = $conn->prepare("INSERT INTO comments (news_id, user_id, text, date) VALUES (:news_id, :user_id, :text, :date)");
$date = date('Y-m-d H:i:s');

foreach ($comments as $c) {
    $stmtComment->execute([
        ':news_id' => $c[0],
        ':user_id' => $c[1],
        ':text' => $c[2],
        ':date' => $date
    ]);
}

echo "English database seeding successfully finished!\n";
echo "Categories: " . count($categories) . "\n";
echo "News Articles: " . count($articles) . "\n";
echo "Comments: " . count($comments) . "\n";
echo "Admin Login: admin@newsportal.ee / 123456\n";
