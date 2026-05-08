<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Position;
use App\Models\Competency;
use App\Models\Indicator;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'name' => 'System Administrator',
                'email' => 'admin@company.com',
                'role' => 'administrator',
                'department' => 'IT',
            ],
            [
                'username' => 'evaluator1',
                'password' => Hash::make('pass123'),
                'name' => 'John Manager',
                'email' => 'john@company.com',
                'role' => 'evaluator',
                'department' => 'Engineering',
            ],
            [
                'username' => 'evaluator2',
                'password' => Hash::make('pass123'),
                'name' => 'Sarah Supervisor',
                'email' => 'sarah@company.com',
                'role' => 'evaluator',
                'department' => 'Design',
            ],
            [
                'username' => 'employee1',
                'password' => Hash::make('pass123'),
                'name' => 'Jane Employee',
                'email' => 'jane@company.com',
                'role' => 'employee',
                'department' => 'Engineering',
            ],
            [
                'username' => 'employee2',
                'password' => Hash::make('pass123'),
                'name' => 'Mike Developer',
                'email' => 'mike@company.com',
                'role' => 'employee',
                'department' => 'Engineering',
            ],
            [
                'username' => 'employee3',
                'password' => Hash::make('pass123'),
                'name' => 'Lisa Analyst',
                'email' => 'lisa@company.com',
                'role' => 'employee',
                'department' => 'Analytics',
            ],
            [
                'username' => 'employee4',
                'password' => Hash::make('pass123'),
                'name' => 'Tom Specialist',
                'email' => 'tom@company.com',
                'role' => 'employee',
                'department' => 'Operations',
            ],
            [
                'username' => 'evaluator3',
                'password' => Hash::make('pass123'),
                'name' => 'James Manager',
                'email' => 'james@company.com',
                'role' => 'evaluator',
                'department' => 'Management',
            ],
        ];

        $userIds = [];
        foreach ($users as $userData) {
            $user = User::create($userData);
            $userIds[$user->username] = $user->id;
        }

        /*$roles = [
            [
                'name' => 'Software Developer',
                'description' => 'Develops and maintains software applications',
                'department' => 'Engineering',
            ],
            [
                'name' => 'Operations Specialist',
                'description' => 'Manages operational processes and workflows',
                'department' => 'Operations',
            ],
            [
                'name' => 'Data Analyst',
                'description' => 'Analyzes data and provides insights',
                'department' => 'Analytics',
            ],
            [
                'name' => 'UI/UX Designer',
                'description' => 'Designs user interfaces and experiences',
                'department' => 'Design',
            ],
            [
                'name' => 'Project Coordinator',
                'description' => 'Coordinates projects and team activities',
                'department' => 'Operations',
            ],
            [
                'name' => 'Manager',
                'description' => 'Ensures the quality of software products',
                'department' => 'Management',
            ],
        ];

        $createdRoles = Role::upsert($roles, ['name'], ['description', 'department']);

        $positions = [
            [
                'name' => 'Senior Developer',
                'level' => 'Senior',
                'salary_range' => '$80k-$120k',
                'description' => 'Leads development projects and mentors junior developers',
            ],
            [
                'name' => 'Operations Manager',
                'level' => 'Manager',
                'salary_range' => '$70k-$100k',
                'description' => 'Oversees operational efficiency and team performance',
            ],
            [
                'name' => 'Data Analyst',
                'level' => 'Mid-level',
                'salary_range' => '$60k-$85k',
                'description' => 'Analyzes business data and creates reports',
            ],
            [
                'name' => 'Senior Designer',
                'level' => 'Senior',
                'salary_range' => '$75k-$110k',
                'description' => 'Creates innovative designs and leads design projects',
            ],
            [
                'name' => 'Project Coordinator',
                'level' => 'Entry-Mid',
                'salary_range' => '$45k-$65k',
                'description' => 'Coordinates project activities and supports team collaboration',
            ],
            [
                'name' => 'Engineering Manager',
                'level' => 'Manager',
                'salary_range' => '$90k-$130k',
                'description' => 'Manages engineering teams and ensures project delivery',
            ],
        ];

        $createdPositions = Position::upsert($positions, ['name'], ['level', 'salary_range', 'description']);*/

        $competencies = [
            ['name' => 'Communication', 'category' => 'Soft Skills', 'description' => 'Ability to convey ideas clearly and effectively', 'weight' => 0.20],
            ['name' => 'Technical Skills', 'category' => 'Technical', 'description' => 'Proficiency in required technologies and tools', 'weight' => 0.25],
            ['name' => 'Leadership', 'category' => 'Management', 'description' => 'Ability to lead teams and projects', 'weight' => 0.15],
            ['name' => 'Problem Solving', 'category' => 'Cognitive', 'description' => 'Analytical thinking and solution development', 'weight' => 0.20],
            ['name' => 'Teamwork', 'category' => 'Soft Skills', 'description' => 'Collaboration and interpersonal skills', 'weight' => 0.10],
            ['name' => 'Data Analysis', 'category' => 'Technical', 'description' => 'Ability to analyze and interpret data', 'weight' => 0.15],
            ['name' => 'Creativity', 'category' => 'Innovation', 'description' => 'Ability to think outside the box and innovate', 'weight' => 0.15],
            ['name' => 'Project Management', 'category' => 'Management', 'description' => 'Planning and executing projects effectively', 'weight' => 0.20],
        ];

        Competency::upsert($competencies, ['name'], ['category', 'description', 'weight']);

        $indicators = [
            ['name' => 'Code Quality', 'category' => 'Technical', 'description' => 'Maintains high standards in code development', 'weight' => 0.20],
            ['name' => 'Productivity', 'category' => 'Performance', 'description' => 'Delivers work on time and meets deadlines', 'weight' => 0.25],
            ['name' => 'Process Efficiency', 'category' => 'Operations', 'description' => 'Optimizes workflows and improves efficiency', 'weight' => 0.15],
            ['name' => 'Innovation', 'category' => 'Innovation', 'description' => 'Introduces new ideas and solutions', 'weight' => 0.15],
            ['name' => 'Team Collaboration', 'category' => 'Soft Skills', 'description' => 'Works effectively with team members', 'weight' => 0.10],
            ['name' => 'Quality Assurance', 'category' => 'Quality', 'description' => 'Ensures high quality in deliverables', 'weight' => 0.20],
            ['name' => 'Data Accuracy', 'category' => 'Technical', 'description' => 'Maintains accuracy in data handling', 'weight' => 0.15],
            ['name' => 'Design Impact', 'category' => 'Creative', 'description' => 'Creates designs that positively impact user experience', 'weight' => 0.20],
            ['name' => 'Project Delivery', 'category' => 'Management', 'description' => 'Successfully delivers projects on time and within scope', 'weight' => 0.25],
        ];

        Indicator::upsert($indicators, ['name'], ['category', 'description', 'weight']);

        $allRoles = Role::all();
        $allPositions = Position::all();

        /*$employees = [
            [
                'user_id' => $userIds['employee1'],
                'name' => 'Jane Employee',
                'role_id' => $allRoles->firstWhere('name', 'Software Developer')?->id ?? 1,
                'position_id' => $allPositions->firstWhere('name', 'Senior Developer')?->id ?? 1,
                'evaluator_id' => $userIds['evaluator1'],
                'department' => 'Engineering',
                'hire_date' => '2023-01-15',
                'status' => 'active',
            ],
            [
                'user_id' => $userIds['employee2'],
                'name' => 'Mike Developer',
                'role_id' => $allRoles->firstWhere('name', 'Software Developer')?->id ?? 1,
                'position_id' => $allPositions->firstWhere('name', 'Senior Developer')?->id ?? 1,
                'evaluator_id' => $userIds['evaluator1'],
                'department' => 'Engineering',
                'hire_date' => '2022-06-10',
                'status' => 'active',
            ],
            [
                'user_id' => $userIds['employee3'],
                'name' => 'Lisa Analyst',
                'role_id' => $allRoles->firstWhere('name', 'Data Analyst')?->id ?? 3,
                'position_id' => $allPositions->firstWhere('name', 'Data Analyst')?->id ?? 3,
                'evaluator_id' => $userIds['evaluator1'],
                'department' => 'Analytics',
                'hire_date' => '2023-03-20',
                'status' => 'active',
            ],
            [
                'user_id' => $userIds['employee4'],
                'name' => 'Tom Specialist',
                'role_id' => $allRoles->firstWhere('name', 'Operations Specialist')?->id ?? 2,
                'position_id' => $allPositions->firstWhere('name', 'Operations Manager')?->id ?? 2,
                'evaluator_id' => $userIds['evaluator2'],
                'department' => 'Operations',
                'hire_date' => '2021-11-05',
                'status' => 'active',
            ],
            [
                'user_id' => null,
                'name' => 'Alice Designer',
                'role_id' => $allRoles->firstWhere('name', 'UI/UX Designer')?->id ?? 4,
                'position_id' => $allPositions->firstWhere('name', 'Senior Designer')?->id ?? 4,
                'evaluator_id' => $userIds['evaluator2'],
                'department' => 'Design',
                'hire_date' => '2022-09-12',
                'status' => 'active',
            ],
            [
                'user_id' => null,
                'name' => 'Bob Coordinator',
                'role_id' => $allRoles->firstWhere('name', 'Project Coordinator')?->id ?? 5,
                'position_id' => $allPositions->firstWhere('name', 'Project Coordinator')?->id ?? 5,
                'evaluator_id' => $userIds['evaluator2'],
                'department' => 'Operations',
                'hire_date' => '2023-07-08',
                'status' => 'active',
            ],
        ];

        foreach ($employees as $empData) {
            Employee::create($empData);
        }*/
    }
}
