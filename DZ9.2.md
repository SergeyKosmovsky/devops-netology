## В рамках основной части необходимо создать собственные workflow для двух типов задач: bug и остальные типы задач. Задачи типа bug должны проходить следующий жизненный цикл:

Open -> On reproduce  
On reproduce <-> Open, Done reproduce  
Done reproduce -> On fix  
On fix <-> On reproduce, Done fix  
Done fix -> On test  
On test <-> On fix, Done  
Done <-> Closed, Open  
