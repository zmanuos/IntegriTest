var menu = [
    {
        id: 'Creaciones',
        title: 'Creaciones',
        icon: 'plus',
        url: 'creations.php',
        color: '#20B2AA',
        subItems: []
    },
    {
        id: 'users_management',
        title: 'Creación de usuarios',
        icon: 'users-cog',
        url: 'users_management.php',
        color: '#6A0DAD',
        subItems: []
    },
    {
        id: 'dashboard',
        title: 'Gestion de usuarios',
        icon: 'users',
        url: 'dashboard.html',
        color: '#FADB4C',
        subItems: [
            {
                id: 'profesores',
                title: 'Profesores',
                url: 'teacher_management.php'
            },
            {
                id: 'alumnos',
                title: 'Alumnos',
                url: 'student_management.php'
            }
        ]
    },
    {
        id: 'security',
        title: 'Asignaciones',
        icon: 'clipboard',
        url: 'assignments.php',
        color: '#c0392b',
        subItems: []
    },
    {
        id: 'security',
        title: 'Consultas',
        icon: 'search',
        url: 'queries.php',
        color: '#27AE60',
        subItems: []
    },
    
];
