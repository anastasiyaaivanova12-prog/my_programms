import pygame
from pygame.locals import (
    K_UP,
    K_DOWN,
    QUIT,
    RLEACCEL,
    KEYDOWN,
    K_LEFT,
    K_RIGHT,
    K_r,
    K_ESCAPE
)
from pygame.sprite import Sprite
from abc import abstractmethod, ABC
import random
import math
import time

SCREEN_WIDTH = 600
SCREEN_HEIGHT = 800

#КЛАСС БИБИЗЯНЫ
class Player(Sprite):
    def __init__(self):
        full_sprite = pygame.image.load("./img/monkey-sprite.png").convert_alpha()
        self.frames = []
        c_fw = 3
        c_fh = 4
        fw = full_sprite.get_width()//c_fw
        fh = full_sprite.get_height()//c_fh
        for i in range(c_fh):
            for j in range(c_fw):
                self.frames.append(
                    full_sprite.subsurface(
                        pygame.Rect(j*fw, i*fh, fw, fh)))
        self.image = self.frames[0]
        self.rect = pygame.Rect(200, 530, 200, 200)
        self.dx = 0
        self.dy = 0
        self.d = 0
        self.d_l = 6
        self.frame_num = self.d

    def move(self):
        self.rect.move_ip(self.dx, self.dy)
        self.draw(keys)
        self.dx = 0
        self.dy = 0

    def update(self, key_pressed):
        if key_pressed[K_RIGHT]:
            self.dx = 23
        elif key_pressed[K_LEFT]:
            self.dx = -23
        if self.rect.left <= 0 and self.dx < 0:
            self.dx = 0
        elif self.rect.right >= SCREEN_WIDTH and self.dx > 0:
            self.dx = 0
        self.move()
        
    def draw(self, key_pressed):
        if key_pressed[K_RIGHT]:
            self.frame_num +=1
            if self.frame_num >= (self.d + 5):
                self.frame_num = self.d

            screen.blit(self.frames[self.frame_num], self.rect)
        elif key_pressed[K_LEFT]:
            self.frame_num +=1
            if self.frame_num < self.d_l or self.frame_num == (len(self.frames)-1):
                self.frame_num = self.d_l

            screen.blit(self.frames[self.frame_num], self.rect)
        else:

            if self.frame_num < (self.d + 5):
                screen.blit(self.frames[0], self.rect)
            elif self.frame_num > (self.d + 5):
                screen.blit(self.frames[6], self.rect)
        #pygame.draw.rect(screen, (255, 0, 0), self.rect, 2) #это красная рамка

    def redraw(self):
        screen.blit(self.frames[self.frame_num], self.rect)

    def collab_with_bomb(self):
        if self.frame_num < (self.d + 5):
                screen.blit(self.frames[5], self.rect)
        elif self.frame_num > (self.d + 5):
                screen.blit(self.frames[11], self.rect)

#КЛАСС КОРЗИНЫ
class Basket(Player):
    def __init__(self):
        Player.__init__(self)
        full_sprite = pygame.image.load("./img/basket-sprite.png").convert_alpha()
        self.frames = []
        c_fw = 1
        c_fh = 4
        fw = full_sprite.get_width()//c_fw
        fh = full_sprite.get_height()//c_fh
        for i in range(c_fh):
            for j in range(c_fw):
                self.frames.append(
                    full_sprite.subsurface(
                        pygame.Rect(j*fw, i*fh, fw, fh)))
        self.image = self.frames[0]
        self.rect = pygame.Rect(200, 475, 200, 200)
        self.d = 0
        self.frame_num = self.d

    def move(self):
        self.rect.move_ip(self.dx, self.dy)
        self.draw()
        self.dx = 0
        self.dy = 0

    def draw(self):
        screen.blit(self.frames[self.frame_num], self.rect)

    def amount_fruit_num(self, fruits_num):
        if amount_fruits == 0:
            self.frame_num = 0
        elif amount_fruits == 15:
            self.frame_num = 1
            sound5.play()
        elif amount_fruits == 30:
            self.frame_num = 2
            sound5.play()
        elif amount_fruits == 45:
            self.frame_num = 3
            sound5.play()
        screen.blit(self.frames[self.frame_num], self.rect)

#КЛАСС ДЛЯ ПАДАЮЩИХ ОБЪЕКТОВ
class Object(Sprite):
    def __init__(self, image_path):
        self.image = pygame.image.load(image_path).convert_alpha()
        self.rect = self.image.get_rect(
                center = (random.randint(10, SCREEN_WIDTH-10),
                          0)
            )
        self.dx = 0
        self.dy = 15

    def draw(self):
        screen.blit(self.image, self.rect)

    def move(self):
        self.rect.move_ip(self.dx, self.dy)
        self.draw()
        self.update()

    def update(self):
        if self.rect.bottom > SCREEN_HEIGHT:
            self.rect = self.image.get_rect(
                center = (random.randint(10, SCREEN_WIDTH-10),
                          -840)
            )

#КЛАСС ДЛЯ БОМБЫ
class Bomb(Object):
    def __init__(self):
        self.image = pygame.image.load("./img/bomb.png").convert_alpha()
        self.rect = self.image.get_rect(
                center = (random.randint(0, SCREEN_WIDTH),
                          random.randint(-10, 0))
            )
        self.dx = 0
        self.dy = 15


pygame.init()

screen = pygame.display.set_mode([600, 800])
#screen.fill((206, 245, 245))
bg = pygame.image.load("./img/fon.png")
bg1 = pygame.image.load("./img/start-window-fon.png")
bg2 = pygame.image.load("./img/game-over-fon.png")
screen.blit(bg, (0, 0))

#фоновая музыка
pygame.mixer.init()
pygame.mixer.music.load("./music/Moonlight Beach.mp3")
pygame.mixer.music.play(-1)

#звуковые эффекты
sound1 = pygame.mixer.Sound("./music/fruit-in-bracket.wav")
sound2 = pygame.mixer.Sound("./music/hawaii_guitar_slide.mp3")
sound3 = pygame.mixer.Sound("./music/boom.mp3")
sound4 = pygame.mixer.Sound("./music/retry.mp3")
sound5 = pygame.mixer.Sound("./music/hihi.mp3")
sound6 = pygame.mixer.Sound("./music/new_record.wav")

clock = pygame.time.Clock()

FALLING_OBJECT_EVENT = pygame.USEREVENT + 1  # уникальный идентификатор нашего события
INTERVAL = 1000  # Интервал появления объектов в миллисекундах (2 секунды)
pygame.time.set_timer(FALLING_OBJECT_EVENT, INTERVAL)

BOOM_EVENT = pygame.USEREVENT + 2
pygame.time.set_timer(BOOM_EVENT, 2200)

#создание спрайта игрока
player = Player()
basket = Basket()

falling_object = []
fruits = []
fruits.append("./img/apple.png")
fruits.append("./img/banana.png")
fruits.append("./img/coconut.png")
fruits.append("./img/orange.png")
fruits.append("./img/peach.png")
fruits.append("./img/plum.png")
fruits.append("./img/strawberry.png")

amount_fruits = 0
font = pygame.font.Font(None, 36)
BLACK = (255, 255, 255)
record = 0
COLOR_RECT = (14, 21, 152)
COLOR_RECORD = (14, 21, 152)

#основной игровой цикл
starting = True
running = False
ending = False
l = 0
m = 0
u = 0
while True:
    if starting:
         for event in pygame.event.get():
            if event.type == pygame.QUIT:
                starting = False
                pygame.quit()
                exit()
            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_RETURN:
                    starting = False
                    running = True
         screen.blit(bg1, (0, 0))
         pygame.display.flip()
    elif running:
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                running = False
                pygame.quit()
                exit()
            if event.type == FALLING_OBJECT_EVENT and len(falling_object) < 8:
                k = random.randint(0, 100)
                bombs = sum(isinstance(obj, Bomb) for obj in falling_object)
                if k>80 and bombs < 4:
                    falling_object.append(Bomb()) 
                else:
                    random_fruit = random.choice(fruits)
                    falling_object.append(Object(random_fruit))

        screen.blit(bg, (0, 0))
        
        pygame.draw.rect(screen, COLOR_RECT, [0, 0, 120, 50], border_radius=10)
        text_surface = font.render(f"Счёт: {amount_fruits}", True, BLACK)

        pygame.draw.rect(screen, (14, 21, 152), [150, 0, 150, 50], border_radius=10)
        record_surface = font.render(f"Рекорд: {record}", True, BLACK)
        
        screen.blit(text_surface, (10, 10))
        screen.blit(record_surface, (155, 10))
        keys = pygame.key.get_pressed()
        player.update(keys)
        basket.update(keys)
        for obj in falling_object:  
            obj.move()
            if isinstance(obj, Bomb) and  pygame.sprite.collide_mask(basket, obj):
                screen.blit(bg, (0, 0))
                pygame.mixer.music.stop()
                player.collab_with_bomb()
                basket.update(keys)
                sound2.play()
                running = False
                ending = True
            elif isinstance(obj, Object) and  pygame.sprite.collide_mask(basket, obj):
                sound1.play()
                amount_fruits += 1
                falling_object.remove(obj)
                basket.amount_fruit_num(amount_fruits)
                if amount_fruits > record and m != 1:
                    COLOR_RECT = (225, 169, 0)
                    m += 1
                    sound6.play()
                    
        clock.tick(17)
        pygame.display.flip()
    elif ending:
        if u != 1:
            pygame.time.delay(2200)
            u += 1
        screen.blit(bg2, (0, 0))
        if amount_fruits > record:
            record = amount_fruits
        pygame.draw.rect(screen, (14, 21, 152), [150, 0, 150, 50], border_radius=10)
        record_surface = font.render(f"Рекорд: {record}", True, BLACK)
        pygame.draw.rect(screen, (14, 21, 152), [0, 0, 120, 50], border_radius=10)
        screen.blit(text_surface, (10, 10))
        screen.blit(record_surface, (155, 10))
        if l != 1:
            sound3.play()
            l += 1
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                ending = False
                pygame.quit()
                exit()
            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_r:
                    sound4.play()
                    pygame.time.delay(2100)
                    running = True
                    ending = False
                    amount_fruits = 0
                    falling_object.clear()
                    l = 0
                    m = 0
                    u = 0
                    basket.amount_fruit_num(amount_fruits)
                    COLOR_RECT = (14, 21, 152)
                    pygame.mixer.music.play(-1)
                elif event.key == pygame.K_ESCAPE:
                    starting = True
                    ending = False
                    amount_fruits = 0
                    falling_object.clear()
                    l = 0
                    m = 0
                    u = 0
                    basket.amount_fruit_num(amount_fruits)
                    COLOR_RECT = (14, 21, 152)
                    pygame.mixer.music.play(-1)
        pygame.display.flip()
