<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();
        
        if ($projects->isEmpty()) {
            echo "No projects found. Please run ProjectsTableSeeder first.\n";
            return;
        }

        $tasks = [
            // E-commerce Platform Development Tasks
            [
                'project_id' => 1,
                'title' => 'Design Database Schema',
                'description' => 'Create comprehensive database schema for e-commerce platform including users, products, orders, and payments.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(30),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 1,
                'title' => 'Implement User Authentication',
                'description' => 'Develop secure user authentication system with JWT tokens and role-based access control.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(20),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 1,
                'title' => 'Product Management System',
                'description' => 'Build product catalog management with categories, inventory tracking, and pricing.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(7),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 1,
                'title' => 'Payment Gateway Integration',
                'description' => 'Integrate Stripe and PayPal payment gateways for secure online transactions.',
                'status' => 'todo',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(14),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 1,
                'title' => 'Order Management System',
                'description' => 'Develop order processing, tracking, and fulfillment management system.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(21),
                'created_by' => $users->random()->id,
            ],

            // Mobile App for Healthcare Tasks
            [
                'project_id' => 2,
                'title' => 'UI/UX Design',
                'description' => 'Design intuitive and accessible user interface for healthcare professionals.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(15),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 2,
                'title' => 'Patient Records Module',
                'description' => 'Develop secure patient records management with HIPAA compliance.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(10),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 2,
                'title' => 'Appointment Scheduling',
                'description' => 'Create appointment booking system with calendar integration.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(25),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 2,
                'title' => 'Telemedicine Features',
                'description' => 'Implement video consultation and remote monitoring capabilities.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(35),
                'created_by' => $users->random()->id,
            ],

            // AI-Powered Analytics Dashboard Tasks
            [
                'project_id' => 3,
                'title' => 'Data Architecture Design',
                'description' => 'Design scalable data architecture for real-time analytics processing.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(5),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 3,
                'title' => 'Machine Learning Models',
                'description' => 'Develop predictive analytics models for business insights.',
                'status' => 'todo',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(30),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 3,
                'title' => 'Dashboard UI Development',
                'description' => 'Create interactive dashboard with charts, graphs, and real-time updates.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(45),
                'created_by' => $users->random()->id,
            ],

            // Website Redesign Project Tasks
            [
                'project_id' => 4,
                'title' => 'Content Audit',
                'description' => 'Review and optimize existing website content for SEO and user experience.',
                'status' => 'done',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(60),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 4,
                'title' => 'Responsive Design Implementation',
                'description' => 'Implement mobile-first responsive design across all pages.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(30),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 4,
                'title' => 'SEO Optimization',
                'description' => 'Implement technical SEO improvements and meta tag optimization.',
                'status' => 'done',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(15),
                'created_by' => $users->random()->id,
            ],

            // Cloud Migration Initiative Tasks
            [
                'project_id' => 5,
                'title' => 'Infrastructure Assessment',
                'description' => 'Evaluate current infrastructure and plan migration strategy.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(45),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 5,
                'title' => 'Database Migration',
                'description' => 'Migrate databases to cloud with minimal downtime.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(10),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 5,
                'title' => 'Application Deployment',
                'description' => 'Deploy applications to cloud infrastructure with CI/CD pipeline.',
                'status' => 'todo',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(25),
                'created_by' => $users->random()->id,
            ],

            // Customer Portal Development Tasks
            [
                'project_id' => 6,
                'title' => 'User Authentication System',
                'description' => 'Implement secure customer login and registration system.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(20),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 6,
                'title' => 'Account Management Features',
                'description' => 'Develop customer account management and profile settings.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(15),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 6,
                'title' => 'Invoice Management',
                'description' => 'Create invoice viewing and payment processing system.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(30),
                'created_by' => $users->random()->id,
            ],

            // Security Audit & Compliance Tasks
            [
                'project_id' => 7,
                'title' => 'Security Assessment',
                'description' => 'Conduct comprehensive security audit of all systems and applications.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->subDays(10),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 7,
                'title' => 'Vulnerability Remediation',
                'description' => 'Address identified security vulnerabilities and implement fixes.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(5),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 7,
                'title' => 'Compliance Documentation',
                'description' => 'Update compliance documentation and create audit reports.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(15),
                'created_by' => $users->random()->id,
            ],

            // API Integration Platform Tasks
            [
                'project_id' => 8,
                'title' => 'API Architecture Design',
                'description' => 'Design scalable API architecture for third-party integrations.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(7),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 8,
                'title' => 'Authentication & Authorization',
                'description' => 'Implement secure API authentication and role-based access control.',
                'status' => 'todo',
                'priority' => 'high',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(20),
                'created_by' => $users->random()->id,
            ],
            [
                'project_id' => 8,
                'title' => 'Third-party Service Integration',
                'description' => 'Develop connectors for popular third-party services and APIs.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $users->random()->id,
                'deadline' => now()->addDays(40),
                'created_by' => $users->random()->id,
            ]
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }

        echo "Created " . count($tasks) . " sample tasks across " . $projects->count() . " projects\n";
    }
} 