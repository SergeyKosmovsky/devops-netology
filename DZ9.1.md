В рамках основной части необходимо создать собственные workflow для двух типов задач: bug и остальные типы задач. Задачи типа bug должны проходить следующий жизненный цикл:

Open -> On reproduce
On reproduce <-> Open, Done reproduce
Done reproduce -> On fix
On fix <-> On reproduce, Done fix
Done fix -> On test
On test <-> On fix, Done
Done <-> Closed, Open




Остальные задачи должны проходить по упрощённому workflow:

Open -> On develop
On develop <-> Open, Done develop
Done develop -> On test
On test <-> On develop, Done
Done <-> Closed, Open
