## В рамках основной части необходимо создать собственные workflow для двух типов задач: bug и остальные типы задач. Задачи типа bug должны проходить следующий жизненный цикл:

Open -> On reproduce  
On reproduce <-> Open, Done reproduce  
Done reproduce -> On fix  
On fix <-> On reproduce, Done fix  
Done fix -> On test  
On test <-> On fix, Done  
Done <-> Closed, Open  
 
![bug](https://user-images.githubusercontent.com/93204208/173383020-f35e8e2f-8ee2-4478-8004-8cdaeacc8901.PNG)



## Остальные задачи должны проходить по упрощённому workflow:

Open -> On develop  
On develop <-> Open, Done develop  
Done develop -> On test  
On test <-> On develop, Done  
Done <-> Closed, Open  

![task](https://user-images.githubusercontent.com/93204208/173383035-23cee8ce-a8f7-4ad9-8277-d95801342411.PNG)
