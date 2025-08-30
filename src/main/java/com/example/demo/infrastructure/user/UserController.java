package com.example.demo.infrastructure.user;

import java.util.List;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import com.example.demo.domain.entities.user.IUser;
import com.example.demo.domain.entities.user.User;
import com.example.demo.domain.usecase.user.CreateUserUseCase;
import com.example.demo.domain.usecase.user.SearchUserByUserName;
import com.example.demo.infrastructure.user.dto.UserRegistrationData;

@RestController
@RequestMapping("/users")
public class UserController {
    private final CreateUserUseCase createUserUseCase;
    private final SearchUserByUserName searchUserByUserName;

    public UserController(CreateUserUseCase createUserUseCase, SearchUserByUserName searchUserByUserName) {
        this.createUserUseCase = createUserUseCase;
        this.searchUserByUserName = searchUserByUserName;
    }

    // Add methods to handle HTTP requests here

    @PostMapping
    public ResponseEntity<IUser> createUser(@RequestBody UserRegistrationData data) {
        IUser createdUser = createUserUseCase.execute(data);
        return new ResponseEntity<>(createdUser, HttpStatus.CREATED);
    }

    @GetMapping("/search")
    public ResponseEntity<List<User>> searchUsers(@RequestParam String username) {
        List<User> users = searchUserByUserName.execute(username);
        return new ResponseEntity<>(users, HttpStatus.OK);
    }
}
