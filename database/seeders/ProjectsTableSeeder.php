<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        $projects = [
            [
                'name' => 'E-commerce Platform Development',
                'description' => 'Develop a comprehensive e-commerce platform with modern features including payment integration, inventory management, and customer analytics.',
                'status' => 'active',
                'owner_id' => $users->random()->id,
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(2),
            ],
            [
                'name' => 'Mobile App for Healthcare',
                'description' => 'Create a mobile application for healthcare providers to manage patient records, appointments, and telemedicine consultations.',
                'status' => 'active',
                'owner_id' => $users->random()->id,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(4),
            ],
            [
                'name' => 'AI-Powered Analytics Dashboard',
                'description' => 'Build an intelligent analytics dashboard that provides real-time insights and predictive analytics for business decision making.',
                'status' => 'planning',
                'owner_id' => $users->random()->id,
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(6),
            ],
            [
                'name' => 'Website Redesign Project',
                'description' => 'Modernize the company website with responsive design, improved SEO, and enhanced user experience.',
                'status' => 'completed',
                'owner_id' => $users->random()->id,
                'start_date' => now()->subMonths(4),
                'end_date' => now()->subMonth(),
            ],
            [
                'name' => 'Cloud Migration Initiative',
                'description' => 'Migrate existing on-premise infrastructure to cloud-based solutions for improved scalability and cost efficiency.',
                'status' => 'active',
                'owner_id' => $users->random()->id,
                'start_date' => now()->subMonths(5),
                'end_date' => now()->addMonths(3),
            ],
            [
                'name' => 'Customer Portal Development',
                'description' => 'Develop a self-service portal for customers to manage their accounts, view invoices, and submit support tickets.',
                'status' => 'planning',
                'owner_id' => $users->random()->id,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(5),
            ],
            [
                'name' => 'Security Audit & Compliance',
                'description' => 'Conduct comprehensive security audit and implement compliance measures for data protection regulations.',
                'status' => 'active',
                'owner_id' => $users->random()->id,
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonth(),
            ],
            [
                'name' => 'API Integration Platform',
                'description' => 'Build a centralized API integration platform to connect various third-party services and internal systems.',
                'status' => 'planning',
                'owner_id' => $users->random()->id,
                'start_date' => now(),
                'end_date' => now()->addMonths(8),
            ]
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        echo "Created " . count($projects) . " sample projects\n";
    }
} 