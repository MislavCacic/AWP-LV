var express = require('express');
var router = express.Router();
var usersRouter = require('./users');

// In-memory storage for projects (in production, use a database)
let projects = [
  {
    id: 1,
    naziv: 'Web aplikacija',
    opis: 'Razvoj web aplikacije za upravljanje projektima',
    cijena: 5000,
    obavljeniPoslovi: 'Dizajn, backend razvoj',
    datumPocetka: '2025-01-01',
    datumZavrsetka: '2025-06-30',
    arhiviran: false,
    voditeljId: null, // Postavi kada se korisnik registrira i kreira projekt
    clanovi: [] // Članovi se povezuju s userId iz registriranih korisnika
  },
  {
    id: 2,
    naziv: 'Mobilna aplikacija',
    opis: 'Razvoj mobilne aplikacije za iOS i Android',
    cijena: 8000,
    obavljeniPoslovi: 'UI/UX dizajn, razvoj iOS verzije',
    datumPocetka: '2025-02-15',
    datumZavrsetka: '2025-08-31',
    arhiviran: false,
    voditeljId: null,
    clanovi: []
  },
  {
    id: 3,
    naziv: 'E-commerce platforma',
    opis: 'Izrada online trgovine s integracijom plaćanja',
    cijena: 12000,
    obavljeniPoslovi: 'Analiza zahtjeva, dizajn baze podataka',
    datumPocetka: '2025-03-01',
    datumZavrsetka: '2025-12-31',
    arhiviran: false,
    voditeljId: null,
    clanovi: []
  }
];

let nextId = 4;

/* GET projects listing. */
router.get('/', function(req, res, next) {
  res.render('projects/index', { 
    title: 'Projekti', 
    projects: projects 
  });
});

/* GET new project form. */
router.get('/new', function(req, res, next) {
  res.render('projects/new', { title: 'Novi projekt' });
});

/* GET projects where user is manager. */
router.get('/my/managed', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const userId = req.session.user.id;
  const managedProjects = projects.filter(p => p.voditeljId === userId);
  
  res.render('projects/my-managed', { 
    title: 'Moji projekti - Voditelj', 
    projects: managedProjects 
  });
});

/* GET projects where user is member. */
router.get('/my/member', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const userId = req.session.user.id;
  const memberProjects = projects.filter(p => 
    p.clanovi.some(clan => clan.userId === userId)
  );
  
  res.render('projects/my-member', { 
    title: 'Moji projekti - Član', 
    projects: memberProjects 
  });
});

/* GET archived projects where user is manager or member. */
router.get('/archive', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const userId = req.session.user.id;
  const archivedProjects = projects.filter(p => 
    p.arhiviran && (
      p.voditeljId === userId || 
      p.clanovi.some(clan => clan.userId === userId)
    )
  );
  
  const users = usersRouter.getUsers();
  
  // Add role information to each project
  const projectsWithRole = archivedProjects.map(project => {
    const isManager = project.voditeljId === userId;
    const isMember = project.clanovi.some(clan => clan.userId === userId);
    const voditelj = users.find(u => u.id === project.voditeljId);
    
    return {
      ...project,
      userRole: isManager ? 'Voditelj' : (isMember ? 'Član' : ''),
      voditeljName: voditelj ? voditelj.username : 'Nepoznat'
    };
  });
  
  res.render('projects/archive', { 
    title: 'Arhiva projekata', 
    projects: projectsWithRole 
  });
});

/* POST create new project. */
router.post('/', function(req, res, next) {
  // Check if user is logged in
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const newProject = {
    id: nextId++,
    naziv: req.body.naziv,
    opis: req.body.opis,
    cijena: parseFloat(req.body.cijena),
    obavljeniPoslovi: req.body.obavljeniPoslovi,
    datumPocetka: req.body.datumPocetka,
    datumZavrsetka: req.body.datumZavrsetka,
    arhiviran: false,
    voditeljId: req.session.user.id, // Set logged in user as project manager
    clanovi: []
  };
  projects.push(newProject);
  res.redirect('/projects');
});

/* GET edit project form. */
router.get('/:id/edit', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is project manager
  if (project.voditeljId !== req.session.user.id) {
    return res.status(403).send('Nemate ovlasti za uređivanje ovog projekta. Samo voditelj projekta može uređivati projekt.');
  }
  
  res.render('projects/edit', { 
    title: 'Uredi projekt', 
    project: project 
  });
});

/* GET delete project. */
router.get('/:id/delete', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is project manager
  if (project.voditeljId !== req.session.user.id) {
    return res.status(403).send('Nemate ovlasti za brisanje ovog projekta. Samo voditelj projekta može obrisati projekt.');
  }
  
  projects = projects.filter(p => p.id !== parseInt(req.params.id));
  res.redirect('/projects');
});

/* POST update project. */
router.post('/:id', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is project manager
  if (project.voditeljId !== req.session.user.id) {
    return res.status(403).send('Nemate ovlasti za uređivanje ovog projekta. Samo voditelj projekta može uređivati projekt.');
  }
  
  project.naziv = req.body.naziv;
  project.opis = req.body.opis;
  project.cijena = parseFloat(req.body.cijena);
  project.obavljeniPoslovi = req.body.obavljeniPoslovi;
  project.datumPocetka = req.body.datumPocetka;
  project.datumZavrsetka = req.body.datumZavrsetka;
  
  res.redirect('/projects');
});

/* GET project details. */
router.get('/:id', function(req, res, next) {
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  const users = usersRouter.getUsers();
  const currentUserId = req.session.user ? req.session.user.id : null;
  
  // Get users that are not already members of this project and not the current user
  const availableUsers = users.filter(user => 
    !project.clanovi.some(clan => clan.userId === user.id) &&
    user.id !== currentUserId // Exclude current logged-in user
  );
  
  // Get full user info for project members
  const membersWithDetails = project.clanovi.map(clan => {
    const user = users.find(u => u.id === clan.userId);
    return {
      ...clan,
      username: user ? user.username : 'Nepoznat korisnik',
      email: user ? user.email : ''
    };
  });
  
  res.render('projects/details', { 
    title: 'Detalji projekta', 
    project: project,
    members: membersWithDetails,
    availableUsers: availableUsers
  });
});

/* POST add team member to project. */
router.post('/:id/members', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is project manager
  if (project.voditeljId !== req.session.user.id) {
    return res.status(403).send('Nemate ovlasti za dodavanje članova. Samo voditelj projekta može dodavati članove tima.');
  }
  
  if (!project.clanovi) {
    project.clanovi = [];
  }
  
  const userId = parseInt(req.body.userId);
  
  // Prevent user from adding themselves
  if (req.session.user && userId === req.session.user.id) {
    return res.status(403).send('Ne možete dodati sami sebe na projekt');
  }
  
  const users = usersRouter.getUsers();
  const user = users.find(u => u.id === userId);
  
  if (!user) {
    return res.status(404).send('Korisnik nije pronađen');
  }
  
  // Check if user is already a member
  if (project.clanovi.some(clan => clan.userId === userId)) {
    return res.redirect('/projects/' + req.params.id);
  }
  
  const newMember = {
    userId: userId,
    uloga: req.body.uloga
  };
  
  project.clanovi.push(newMember);
  res.redirect('/projects/' + req.params.id);
});

/* GET delete team member from project. */
router.get('/:projectId/members/:userId/delete', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.projectId));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is project manager
  if (project.voditeljId !== req.session.user.id) {
    return res.status(403).send('Nemate ovlasti za uklanjanje članova. Samo voditelj projekta može uklanjati članove tima.');
  }
  
  if (project.clanovi) {
    project.clanovi = project.clanovi.filter(m => m.userId !== parseInt(req.params.userId));
  }
  
  res.redirect('/projects/' + req.params.projectId);
});

/* GET toggle archive status. */
router.get('/:id/toggle-archive', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is project manager
  if (project.voditeljId !== req.session.user.id) {
    return res.status(403).send('Nemate ovlasti za arhiviranje projekta. Samo voditelj projekta može arhivirati projekt.');
  }
  
  project.arhiviran = !project.arhiviran;
  res.redirect('/projects/' + req.params.id);
});

/* GET edit completed work (for members). */
router.get('/:id/edit-work', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is a member
  const isMember = project.clanovi.some(clan => clan.userId === req.session.user.id);
  if (!isMember) {
    return res.status(403).send('Nemate pristup ovom projektu');
  }
  
  res.render('projects/edit-work', { 
    title: 'Uredi obavljene poslove', 
    project: project 
  });
});

/* POST update completed work (for members). */
router.post('/:id/update-work', function(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  // Check if user is a member
  const isMember = project.clanovi.some(clan => clan.userId === req.session.user.id);
  if (!isMember) {
    return res.status(403).send('Nemate pristup ovom projektu');
  }
  
  project.obavljeniPoslovi = req.body.obavljeniPoslovi;
  res.redirect('/projects/my/member');
});

module.exports = router;
