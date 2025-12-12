const express = require('express');
const router = express.Router();
const Project = require('../models/project');
const Member = require('../models/member');

// GET /projects - lista projekata
router.get('/', async (req, res) => {
  const projects = await Project.find();
  res.render('projects/index', { projects });
});

// GET /projects/new - forma za novi projekt
router.get('/new', (req, res) => {
  res.render('projects/new');
});

// POST /projects - kreiranje projekta
router.post('/', async (req, res) => {
  const project = new Project(req.body);
  await project.save();
  res.redirect('/projects');
});

// GET /projects/:id - detalji
//router.get('/:id', async (req, res) => {
 // const project = await Project.findById(req.params.id);
 // res.render('projects/show', { project });
//});

// GET /projects/:id - detalji + članovi tima
router.get('/:id', async (req, res, next) => {
  try {
    const project = await Project.findById(req.params.id);
    if (!project) return res.status(404).send('Projekt nije pronađen');

    const members = await Member.find({ project: req.params.id });
    res.render('projects/show', { project, members });
  } catch (err) {
    next(err);
  }
});

//router.get('/:id', async (req, res, next) => {
  //try {
  //  const project = await Project.findById(req.params.id);
 //   if (!project) {
  //    return res.status(404).send('Projekt nije pronađen');
   // }
 //   res.render('projects/show', { project }); 
  //} catch (err) {
 //   next(err);
 // }
//});

// GET /projects/:id/edit - forma za uređivanje
router.get('/:id/edit', async (req, res) => {
  const project = await Project.findById(req.params.id);
  res.render('projects/edit', { project });
});

// PUT /projects/:id - forma za ažuriranje
router.put('/:id', async (req, res) => {
  await Project.findByIdAndUpdate(req.params.id, req.body);
  res.redirect('/projects');
});

// DELETE /projects/:id - forma za brisanje
router.delete('/:id', async (req, res) => {
  await Project.findByIdAndDelete(req.params.id);
  res.redirect('/projects');
});

// POST /projects/:id/members - dodaj člana tima
router.post('/:id/members', async (req, res, next) => {
  try {
    const { ime, prezime, uloga } = req.body;
    await Member.create({ ime, prezime, uloga, project: req.params.id });
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    next(err);
  }
});

module.exports = router;
